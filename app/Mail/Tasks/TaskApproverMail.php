<?php

namespace App\Mail\Tasks;

use App\Constants\StatusConstants;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskApproverMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $user;
    public $url;

    /**
     * Create a new message instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->user = $task->user;
        $this->url = route("tasks.show", $task->id);
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
            'message' => "Your approval is required for Task#: ".$this->task->code,
            'user' => $this->task->assignee,
            'url' => route("tasks.show", $this->task->id),
        ];

        return $this->subject('Approval Required')
            ->markdown('emails.tasks.approval', $data);
    }
}
