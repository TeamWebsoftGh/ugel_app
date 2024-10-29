<?php

namespace App\Mail\Tasks;

use App\Constants\StatusConstants;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskDeclineMail extends Mailable
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

        if ($this->task->status_id == StatusConstants::SUBMITTED)
        {
            $data['message'] = $this->task->remarks??"Task has been sent back. Review and resubmit.";
            return $this->subject('Task Returned')
                ->markdown('emails.tasks.task.task-status-change', $data);
        }

        if ($this->task->taskStatus->id == StatusConstants::ONHOLD)
        {
            $data['message'] = "Budget/Resource rejected by ".user()->fullname." Revise the budget/resource and submit for approval.";;
            return $this->subject('Budget/Resource Rejected')
                ->markdown('emails.tasks.task-status-change', $data);
        }


        return $this->subject('Task Status Changed')
            ->markdown('emails.tasks.task.task-status-change', $data);
    }
}
