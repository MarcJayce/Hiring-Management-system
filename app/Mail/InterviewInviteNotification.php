<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewInviteNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $emailContent;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $candidate
     * @param  string  $emailContent  The full HTML email body
     */
    public function __construct($candidate, $emailContent)
    {
        $this->candidate = $candidate;
        $this->emailContent = $emailContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pre-Interview Form')
            ->view('emails.pre-interview')
            ->with([
                'candidate' => $this->candidate,
                'emailContent' => $this->emailContent,
            ]);
    }
}
