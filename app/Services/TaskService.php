<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Constants\StatusConstants;
use App\Events\NewCommentEvent;
use App\Events\NewTaskEvent;
use App\Events\TaskStatusChangeEvent;
use App\Mail\Tasks\TaskDeclineMail;
use App\Models\Common\DocumentUpload;
use App\Models\Task\CheckListItem;
use App\Models\Task\Task;
use App\Models\Task\TaskComment;
use App\Models\Task\Timesheet;
use App\Repositories\Interfaces\ITaskRepository;
use App\Repositories\Workflow\Interfaces\IWorkflowPositionRepository;
use App\Services\Helpers\PropertyHelper;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ITaskService;
use App\Traits\TaskUtil;
use App\Traits\UploadableTrait;
use App\Traits\WorkflowUtil;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 *
 */
class TaskService extends ServiceBase implements ITaskService
{
    use UploadableTrait, WorkflowUtil;

    /**
     * @var ITaskRepository
     */
    private $taskRepo;
    private $wfPositionRepo;

    /**
     * TaskService constructor.
     *
     * @param ITaskRepository $taskRepository
     */
    public function __construct(ITaskRepository $taskRepository, IWorkflowPositionRepository $wkf_position)
    {
        parent::__construct();
        $this->taskRepo = $taskRepository;
        $this->wfPositionRepo = $wkf_position;
    }

    /**
     * List all the Tasks
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listActiveTasks(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->taskRepo->listTasks($filter);
    }

    /**
     * List all the Tasks
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listTasks(array $filter = [], string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->taskRepo->listTasks($filter);
    }

    /**
     * Create the Tasks
     *
     * @param array $params
     * @return Response
     */
    public function createTask(array $params)
    {
        //Declaration
        $task = null;

        //Process Request
        try {
            //if((!$this->canAccessItem($params['assignee_id']) && !user()->hasPermission('create-all-tasks')) || user()->id == $params['assignee_id'])
            if((!$this->canAccessItem($params['assignee_id']) && !user()->can('create-all-tasks')))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You are not allowed to create task for selected employee.";
                return $this->response;
            }
            $params['code'] = generate_task_number();
            $params['user_id'] = user()->id;
            $params['status_id'] = StatusConstants::PENDING;
            $params['stage'] = 'employee';
            $params['approver_id'] = optional($this->getSupervisor($params['assignee_id']))->id;
            $task = $this->taskRepo->createTask($params);

            if (isset($params['task_files'])) {
                $files = collect($params['task_files']);
                $this->saveDocuments($files, $task, $task->title);
            }

            if (isset($params['always_copy'])) {
                $task->notifyUsers()->sync($params['always_copy']);
            }
            event(new NewTaskEvent($task));
        } catch (\Exception $e) {
            log_error(format_exception($e), new Task(), 'create-task-failed');
        }

        //Check if Task was created successfully
        if (!$task)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-task-successful';
        $auditMessage = "New task created with Task #: ".$task->code;

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $task;

        return $this->response;
    }


    /**
     * Find the Task by id
     *
     * @param int $id
     *
     * @return Task
     */
    public function findTaskById(int $id): Task
    {
        return $this->taskRepo->findTaskById($id);
    }

    /**
     * Update Task
     *
     * @param array $params
     * @param Task $task
     * @return Response
     */
    public function updateTask(array $params, Task $task)
    {
        //Declaration
        $result = false;
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        //Process Request
        try {
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            if(isset($params['stage']) && $params['stage'] == "budget")
            {
                $params['status_id'] = StatusConstants::ONHOLD;
                $auditMessage = "Budget/Resources Added.";
            }

            if(isset($params['status_id']) && $params['status_id'] != $task->status_id)
            {
                if($params['status_id'] == StatusConstants::SUBMITTED)
                {
                    if(count($task->timesheets) < 1){
                        $this->response->status = ResponseType::ERROR;
                        $this->response->message = "No activity recorded.";

                        return $this->response;
                    }else{
                        $params['submitted_at'] = Carbon::now();
                        $auditMessage = "Task marked as completed.";
                    }
                }else if($params['status_id'] == StatusConstants::COMPLETED){
                    $params['completed_at'] = Carbon::now();
                    $auditMessage = "Completed task accepted and KPI awarded.";
                }else if($params['status_id'] == StatusConstants::ACCEPTED){
                    $auditMessage = "Task accepted.";
                }

                event(new TaskStatusChangeEvent($task, $auditMessage));
            }
            if (isset($params['always_copy'])) {
                $task->notifyUsers()->sync($params['always_copy']);
            }

            $result = $this->taskRepo->updateTask($params, $task);
            if (isset($params['task_files'])) {
                $files = collect($params['task_files']);
                $this->saveDocuments($files, $task, $task->title);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $task, 'update-task-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-task-successful';

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $task;

        return $this->response;
    }

    /**
     * @param int $status
     * @param Task $task
     * @return Response
     */
    public function changeStatus(int $status, Task $task)
    {
        //Declaration
        $response = false;
        $data = [];
        $auditMessage = 'Task status successfully updated. Task #: '.$task->code;

        //Process Request
        try {
            if(!$this->canAccessItem($task->assignee_id, $task->user_id) && !user()->can('update-all-tasks'))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }

            if($status == StatusConstants::DECLINED && $task->status_id == StatusConstants::SUBMITTED)
            {
                $data['status_id'] = StatusConstants::INPROGRESS;
                $data['stage'] = "employee";
                $auditMessage = "Task sent back to employee for update and resubmission.";
            }elseif($status == StatusConstants::DECLINED && $task->status_id == StatusConstants::ONHOLD)
            {
                $data['status_id'] = StatusConstants::PENDING;
                $data['stage'] = "employee";
                $auditMessage = "Budget/Resource sent back to employee for update and resubmission.";
            }elseif($status == StatusConstants::REOPENED && $task->status_id == StatusConstants::COMPLETED)
            {
                $data['status_id'] = StatusConstants::REOPENED;
                $data['stage'] = "employee";
                $auditMessage = "Budget/Resource sent back to employee for update and resubmission.";
            }

            $response = $this->taskRepo->update($data, $task->id);

            send_mail(TaskDeclineMail::class, $task, $task->assignee);

        } catch (\Exception $e) {
            log_error(format_exception($e), $task, "change-status-failed");
        }

        if (!$response)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }


        //Audit Trail
        $logAction = "change-status-successful";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Task $task
     * @return Response
     */
    public function deleteTask(Task $task)
    {
        //Declaration
        $result = false;
        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            if ($task->status_id != StatusConstants::PENDING || count($task->timesheets) > 0)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete this Task.";

                return $this->response;
            }

            $result = $this->taskRepo->deleteTask($task);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'delete-task-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-task-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @return array
     */
    public function getCreateTask()
    {
        $task = new Task();

        return [
            'priorities' => TaskUtil::getPriorities(),
            'task' => $task,
            'statuses' => TaskUtil::getAllStatuses(),
            'employees' => PropertyHelper::getAll(),
            'tasks' => $this->listTasks()->whereIn('status_id', [StatusConstants::PENDING, StatusConstants::ACCEPTED, StatusConstants::INPROGRESS]),
        ];
    }

    /**
     * @param array $data
     * @param Task $task
     * @return Response
     */
    public function postComment(array $data, Task $task)
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $data['user_id'] = user()->id;
            $result = $task->taskComments()->create($data);
            event(new NewCommentEvent($result));

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'add-task-comment-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'add-task-comment-successful';
        $auditMessage = "Comment added successfully.";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param TaskComment $comment
     * @param Task $task
     * @return Response
     */
    public function deleteComment(TaskComment $comment, Task $task): Response
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $result = $comment->delete();

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'delete-task-comment-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-task-comment-successful';
        $auditMessage = "Comment deleted.";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param array $data
     * @param Task $task
     * @return Response
     */
    public function uploadDocument(array $data, Task $task)
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $files = collect($data['task_files']);
            $result = $this->saveDocuments($files, $task, $task->title);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'upload-document-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'upload-document-successful';
        $auditMessage = "Document uploaded successfully.";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param DocumentUpload $document
     * @param Task $task
     * @return Response
     */
    public function deleteDocument(DocumentUpload $document, Task $task)
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }

            $result = $this->taskRepo->deleteDocument($document);

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'delete-task-document-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-task-document-successful';
        $auditMessage = "File deleted.";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param DocumentUpload $document
     * @param Task $task
     * @return Response
     */
    public function saveActivity(array $data, Task $task): Response
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $data['user_id'] = user()->id;

            $result = $task->timesheets()->updateOrCreate(['id' => $data['id']], $data);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'create-update-activity-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-update-activity-successful';
        $auditMessage = $data['id'] == ''?'Activity successfully added':'Activity successfully updated';

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param DocumentUpload $document
     * @param Task $task
     * @return Response
     */
    public function deleteActivity(Timesheet $activity, Task $task): Response
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $result = $activity->delete();

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'delete-task-activity-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-task-activity-successful';
        $auditMessage = "Activity deleted.";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param array $data
     * @param Task $task
     * @return Response
     */
    public function saveObjective(array $data, Task $task): Response
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $data['user_id'] = user()->id;
            $result = $task->objectives()->updateOrCreate(['id' => $data['id']], $data);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'create-update-objective-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-update-objective-successful';
        $auditMessage = $data['id'] == ''?'Objective successfully added':'Objective successfully updated';

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param CheckListItem $checkListItem
     * @param Task $task
     * @return Response
     */
    public function deleteObjective(CheckListItem $checkListItem, Task $task): Response
    {
        //Declaration
        $result = false;

        try{
            if(!$this->canAccessTask($task))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_NOT_AUTHORIZED;

                return $this->response;
            }
            $result = $checkListItem->delete();

        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $task, 'delete-task-objective-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-task-objective-successful';
        $auditMessage = "Objective deleted.";

        log_activity($auditMessage, $task, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    private function canAccessTask(Task $task)
    {
        if($task->status_id == StatusConstants::COMPLETED)
        {
            return false;
        }

        if(user()->can('update-all-tasks'))
        {
            return true;
        }

        if($this->canAccessItem($task->assignee_id, $task->user_id) && !$task->is_closed)
        {
            return true;
        }

        return false;
    }
}
