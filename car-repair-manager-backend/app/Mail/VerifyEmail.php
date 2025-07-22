<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $verificationCode;
    public $user;
    public $action;

    public $subject;
    /**
     * Create a new message instance.
     */
    public function __construct($user, $verificationCode, $action, $subject) {
        
        $this->user = $user;
        $this->verificationCode = $verificationCode;
        $this->action = $action;
        $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {

        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {

        return new Content(
            //view: 'view.name',
            view: $this->action,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {
        
        return [];
    }
}
