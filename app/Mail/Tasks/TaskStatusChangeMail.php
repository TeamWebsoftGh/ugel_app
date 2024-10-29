<?php

namespace App\Mail\Tasks;

use App\Constants\StatusConstants;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Create a new message instance.
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'task' => $this->task,
            'message' => "Task Status has changed to ".optional($this->task->taskStatus)->name,
            'user' => $this->task->assignee,
            'url' => route("tasks.show", $this->task->id),
        ];

        if ($this->task->taskStatus->id == StatusConstants::SUBMITTED)
        {
            $data['message'] = "Task has been marked as completed and submitted for final approval.";
            return $this->subject('Task Submitted')
                ->markdown('emails.tasks.task-status-change', $data);
        }

        if ($this->task->taskStatus->id == StatusConstants::COMPLETED)
        {
            $data['message'] = "Completed task approved and KPI awarded.";
            return $this->subject('Task Completed')
                ->markdown('emails.tasks.task-status-change', $data);
        }

        if ($this->task->taskStatus->id == StatusConstants::ACCEPTED)
        {
            $data['message'] = "Task accepted. You can start working on the task by adding the activities.";
            return $this->subject('Task Accepted')
                ->markdown('emails.tasks.task-status-change', $data);
        }

        return $this->subject('Task Status Changed')
            ->markdown('emails.tasks.task-status-change', $data);
    }
}
