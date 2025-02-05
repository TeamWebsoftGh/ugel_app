<?php

namespace App\Mail\Tasks;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTaskMail extends Mailable
{
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
        $emails = $this->task->notifyUsers()->pluck('email')->toArray();
        $data = [
            'user' => $this->task->user,
            'task' => $this->task,
            'assignee' => $this->task->assignee,
        ];

        if (count($emails) > 0){
            return $this->subject('New Task Assigned.')
                ->markdown('emails.tasks.new-task-assigner', $data)->cc($emails);
        }

        return $this->subject('New Task Assigned.')
            ->markdown('emails.tasks.new-task-assigner', $data);
    }
}
