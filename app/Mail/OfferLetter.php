<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfferLetter extends Mailable
{
    use SerializesModels;

    public $emailContent;

    public function __construct($emailContent)
    {
        $this->emailContent = $emailContent;
    }

    public function build()
    {
        $email = $this->view('emails.offer-email')
            ->with(['emailContent' => $this->emailContent])
            ->subject('Offer Letter from Chimes Consulting');

        // Attach any uploaded files
        if (request()->hasFile('attachments')) {
            foreach (request()->file('attachments') as $file) {
                $email->attach($file->getRealPath(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ]);
            }
        }

        return $email;
    }
}
