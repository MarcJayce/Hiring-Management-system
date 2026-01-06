<?php

namespace App\Http\Controllers;

use App\Mail\CandidateHired;
use App\Models\ApplicantDetails;
use App\Models\JobPosition;
use App\Models\InterviewSchedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OfferLetter;
use App\Mail\RejectionNotification;
use App\Mail\InterviewInviteNotification;

class InternCandidateController
{
    public function index()
    {
        $type = request()->get('type', 'all');
        $step = request()->get('step', 'screening');

        // Get all job positions containing "Intern"
        $internPositions = JobPosition::where('position_title', 'LIKE', '%Intern%')->pluck('id');

        // Fetch candidates based on type filter
        $candidates = ApplicantDetails::with(['internship', 'interviewSchedules' => function ($query) {
            $query->orderByDesc('date')->orderBy('time');; // Fetch the latest interview schedule
        }]) // Eager load interviewSchedules
            ->when($type == 'intern', function ($query) use ($internPositions) {
                return $query->whereIn('position_id', $internPositions);
            })
            ->when($type == 'employee', function ($query) use ($internPositions) {
                return $query->whereNotIn('position_id', $internPositions);
            })
            // filter for Shortlist Tab
            ->when($step === 'screening', function ($query) {
                return $query->whereNotIn('status', ['Shortlisted', 'For Interview', 'Scheduled for Interview', 'Completed Interview', 'Offer Made', 'Hired', 'Rejected', 'Rejected by Applicant'])
                    ->orWhereNull('status');
            })
            ->when($step === 'shortlisted', function ($query) {
                return $query->where('status', 'Shortlisted');
            })
            ->when($step === 'for_interview', function ($query) {
                return $query->where('status', 'For Interview');
            })
            ->when($step === 'interview_schedule', function ($query) {
                return $query->where('status', 'Scheduled for Interview');
            })
            ->when($step === 'completed_interview', function ($query) { 
                return $query->where('status', 'Completed Interview');
            })  
            ->when($step === 'offer', function ($query) {
                return $query->whereIn('status', ['Offer Made', 'Offer Accepted']);
            })
            ->when($step === 'hired', function ($query) {
                return $query->where('status', 'Hired');
            })
            ->when($step === 'rejected', function ($query) {  // Add this to filter rejected candidates
                return $query->whereIn('status', ['Rejected', 'Rejected by Applicant']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Set candidate type and position
        foreach ($candidates as $candidate) {
            $jobPosition = JobPosition::find($candidate->position_id);

            if (in_array($candidate->position_id, $internPositions->toArray())) {
                $candidate->type = 'Intern';
                $candidate->position = $jobPosition->position_title ?? 'Internship';
            } else {
                $candidate->type = 'Employee';
                $candidate->position = $jobPosition->position_title ?? 'Employee';
            }
        }

        return view('sidebar_list.candidates', compact('candidates'));
    }



    public function show($id)
    {
        $candidate = ApplicantDetails::with(['jobSpecifics', 'jobPosition', 'education', 'internship', 'skillsExperience', 'otherInfo', 'employeeEducation', 'skillsAbilities'])
            ->findOrFail($id);

        // Determine type based on job position
        $candidate->type = stripos($candidate->jobPosition->position_title ?? '', 'intern') !== false ? 'Intern' : 'Employee';

        return view('sidebar_list.candidates-view', compact('candidate'));
    }

    public function shortlist($id)
    {
        $candidate = ApplicantDetails::findOrFail($id);

        // Store the previous status in the session
        Session::put("previous_status_{$id}", $candidate->status);

        $candidate->status = 'Shortlisted';
        $candidate->save();

        return response()->json(['success' => true, 'message' => "{$candidate->first_name} has been shortlisted!"]);
    }

    public function moveToInterview(Request $request, $candidateId)
    {
        $candidate = ApplicantDetails::findOrFail($candidateId);
        // Store the previous status in the session
        Session::put("previous_status_{$candidateId}", $candidate->status);

        $candidate->status = 'For Interview';
        $candidate->save();

        return response()->json(['success' => true, 'message' => "{$candidate->first_name} has been moved to For Interview!"]);
    }

    // New undo method
    public function undoStatus($id)
    {
        $candidate = ApplicantDetails::findOrFail($id);

        // Get the previous status from the session
        $previousStatus = Session::get("previous_status_{$id}");

        if ($previousStatus) {
            $candidate->status = $previousStatus;
            $candidate->save();

            // Clear the session after undoing
            Session::forget("previous_status_{$id}");

            return response()->json(['success' => true, 'message' => "Action undone! {$candidate->first_name} has been restored to {$previousStatus}."]);
        }

        return response()->json(['success' => false, 'message' => 'No previous action to undo.']);
    }

    public function updateAvailability(Request $request)
    {
        $validatedData = $request->validate([
            'availability' => 'required|string|max:255',
            'applicant_id' => 'required|exists:applicant_details,id',
        ]);

        $candidate = ApplicantDetails::findOrFail($validatedData['applicant_id']);
        $candidate->availability = $validatedData['availability'];
        $candidate->save();

        return response()->json(['success' => true, 'message' => 'Availability updated successfully!']);
    }

    public function sendInterviewInvite(Request $request, $candidateId)
    {
        $request->validate([
            'emailContent' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
        ]);

        $candidate = ApplicantDetails::findOrFail($candidateId);

        try {
            Mail::to($candidate->email)->send(new InterviewInviteNotification($candidate, $request->emailContent));

            $candidate->email_status = 'Interview Invite Sent';
            $candidate->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function scheduleInterview(Request $request, $candidateId)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'interviewer' => 'required|exists:users,id',
                'interview_date' => 'required|date',
                'interview_time' => 'required|date_format:H:i',
                'location' => 'required|string|max:255',
            ]);

            // Find the candidate
            $candidate = ApplicantDetails::findOrFail($candidateId);

            // Store previous status for undo functionality
            Session::put("previous_status_{$candidateId}", $candidate->status);

            // Convert interview date and time to a Carbon instance


            // Create a new interview schedule entry
            $interviewSchedule = new InterviewSchedules();
            $interviewSchedule->applicant_id = $candidateId;
            $interviewSchedule->interviewer_id = $validated['interviewer'];
            $interviewSchedule->interview_date = $validated['interview_date'];
            $interviewSchedule->interview_time = $validated['interview_time'];
            $interviewSchedule->location = $validated['location'];
            $interviewSchedule->save();

            // Optionally, update the candidate status
            $candidate->status = 'Scheduled for Interview';
            $candidate->save();

            // Return success response
            return response()->json(['success' => true, 'message' => "{$candidate->first_name} has been scheduled for an interview."]);
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error scheduling interview: " . $e->getMessage());

            // Return error response
            return response()->json(['success' => false, 'message' => 'An error occurred while scheduling the interview. Please try again later.'], 500);
        }
    }



    public function markAsCompleted($id)
    {
        $candidate = ApplicantDetails::find($id);

        // Store previous status for undo functionality
        Session::put("previous_status_{$id}", $candidate->status);

        if (!$candidate) {
            return response()->json(['success' => false, 'message' => 'Candidate not found!']);
        }

        $candidate->status = "Completed";
        $candidate->save();

        return response()->json(['success' => true]);
    }
    public function makeOffer(Request $request, $candidateId)
    {
        $request->validate([
            'offer_date' => 'required|date',
            'start_date' => 'required|date',
            'offer_end_date' => 'required|date',
            'email_content' => 'required|string',
            'candidate_id' => 'required|integer',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);
        $candidate = ApplicantDetails::findOrFail($candidateId);

        // Update Details
        $candidate->offer_date = $request->offer_date;
        $candidate->start_date = $request->start_date;
        $candidate->offer_end_date = $request->offer_end_date;
        $candidate->status = 'Offer Made';
        $candidate->save();
        $emailContent = $request->email_content;

        try {
            Mail::to($candidate->email)->send(
                new OfferLetter($request->email_content, $request->file('attachments', []))
            );;
            return response()->json(['success' => true, 'message' => 'Offer sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error sending the offer.'], 500);
        }
    }

    public function markOfferAccepted($id)
    {
        $candidate = ApplicantDetails::findOrFail($id);

        // Assuming there's a related applicant_detail model
        $candidate->status = 'Offer Accepted';
        $candidate->save();

        return response()->json(['message' => 'Status updated.']);
    }

    public function hireCandidate(Request $request, $id)
    {
        $candidate = ApplicantDetails::findOrFail($id);
        Session::put("previous_status_{$id}", $candidate->status);

        // Validate request
        $request->validate([
            'department' => 'required|string|max:255',
            'hiring_date' => 'required|date',
        ]);

        // Manually setting attributes before saving
        $candidate->department = $request->department;
        $candidate->hiring_date = $request->hiring_date;
        $candidate->status = 'Hired'; // Move candidate to Hired tab

        // Save updates
        $candidate->save();

        return response()->json(['success' => true]);
    }

    public function sendHireEmail(Request $request, $candidateId)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $candidate = ApplicantDetails::findOrFail($candidateId);

        try {
            Mail::to($request->email)->send(new CandidateHired($candidate));
            $candidate->email_status = 'Hiring Email Sent';
            $candidate->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function rejectCandidate($id)
    {
        $candidate = ApplicantDetails::findOrFail($id);
        Session::put("previous_status_{$id}", $candidate->status);
        $candidate->update([
            'status' => 'Rejected'
        ]);
        $candidate->interviewSchedules()->where('status', 'Pending')->delete();
        return response()->json(['success' => true]);
    }

    public function sendRejectionEmail(Request $request, $candidateId)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $candidate = ApplicantDetails::with('jobPosition')->findOrFail($candidateId);

        try {
            $applicationType = strtolower($candidate->jobPosition->application_type);

            if ($applicationType === 'intern') {
                $emailType = 'intern';
            } else {
                $emailType = 'regular';
            }

            Mail::to($request->email)->send(new RejectionNotification($candidate, $emailType));

            $candidate->email_status = 'Rejection Email Sent';
            $candidate->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }



    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = ApplicantDetails::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->select('id', \DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))
            ->limit(10)
            ->get();

        return response()->json($results);
    }


    public function reconsider(Request $request, $id)
    {
        $candidate = ApplicantDetails::with('interviewSchedules')->findOrFail($id);

        $hasNonPendingInterview = $candidate->interviewSchedules
            ->where('status', '!=', 'Pending')
            ->isNotEmpty();

        if ($hasNonPendingInterview) {
            $candidate->status = 'Completed Interview';
            $message = 'Candidate has been moved to Completed Interview.';
        } elseif ($candidate->interviewSchedules->isEmpty()) {
            $candidate->status = 'For Screening';
            $message = 'Candidate has been moved to Screening.';
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Candidate has only pending interviews.',
            ]);
        }

        $candidate->save();

        return response()->json([
            'success' => true,
            'status' => $candidate->status,
            'message' => $message,
        ]);
    }

    public function rejectByApplicant($id)
    {
        $applicant = ApplicantDetails::findOrFail($id);
        $applicant->status = 'Rejected by Applicant';
        $applicant->save();

        return redirect()->back()->with('success', 'Status updated to "Rejected by Applicant".');
    }
}
