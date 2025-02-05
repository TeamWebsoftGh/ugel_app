<?php

namespace App\Mail\Workflow;

use App\Models\Workflow\FlowTypeRequestApprover;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingApprovalAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $flowTypeRequestApprover;
    public $recipientType;
    public $productType;

    public function __construct(FlowTypeRequestApprover $flowTypeRequestApprover, $recipientType, $productType)
    {
        $this->flowTypeRequestApprover = $flowTypeRequestApprover;
        $this->recipientType = $recipientType;
        $this->productType = $productType;
    }

    public function build()
    {
        return $this->subject('Approval Request Pending')
                    ->markdown('emails.workflow.pending_approval')
                    ->with([
                        'flowTypeRequestApprover' => $this->flowTypeRequestApprover,
                        'recipientType' => $this->recipientType,
                        'productType' => $this->productType,
                    ]);
    }
}
