<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ApplicantDetails;

class CandidateHired extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $hiringDate;
    public $department;

    public function __construct(ApplicantDetails $candidate)
    {
        $this->candidate = $candidate;

        // Format hiring date in the constructor
        $this->hiringDate = \Carbon\Carbon::parse($candidate->hiring_date)->format('D, M j, Y');
        $this->department = $candidate->department;
    }

    public function build()
    {
        return $this->subject('🎉 You Have Been Hired!')
            ->view('emails.hired')
            ->with([
                'candidate' => $this->candidate,
                'hiringDate' => $this->hiringDate,
                'department' => $this->department,
            ]);
    }
}
