<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ApplicantDetails;

class RejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $emailType;
    public $candidate;

    /**
     * Create a new message instance.
     *
     * @param  ApplicantDetails  $candidate
     * @return void
     */
    public function __construct($candidate, $emailType = 'regular')
    {
        $this->candidate = $candidate;
        $this->emailType = $emailType;
    }

    public function build()
    {
        if ($this->emailType === 'intern') {
            return $this->subject('Internship Application Status')
                ->view('emails.rejection_intern')
                ->with(['candidate' => $this->candidate]);
        } else {
            return $this->subject('Application Status')
                ->view('emails.rejection_regular')
                ->with(['candidate' => $this->candidate]);
        }
    }
}
