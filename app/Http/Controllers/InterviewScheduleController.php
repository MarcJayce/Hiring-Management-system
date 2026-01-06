<?php

namespace App\Http\Controllers;

use App\Models\ApplicantDetails;
use App\Models\InterviewSchedules;
use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewInvitation;
use Illuminate\Support\Facades\Log;

class InterviewScheduleController extends Controller
{
    public function create()
    {

        $applicants = ApplicantDetails::with('jobPosition')
            ->whereIn('status', ['For Interview', 'Completed Interview'])
            ->get();
        $users = User::all();

        return view('interviews.interview_schedule', compact('applicants', 'users'));
    }

    public function createWithApplicant($id)
    {
        $applicants = ApplicantDetails::with('jobPosition')
            ->whereIn('status', ['For Interview', 'Completed Interview'])
            ->get();
        $users = User::all();

        return view('interviews.interview_schedule', compact('applicants', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|int',
            'interview_type' => 'required|string',
            'interview_date' => 'required|date',
            'interview_time' => 'required|string',
            'interview_duration' => 'required|string',
            'interview_location' => 'required|string',
            'interview_round' => 'required|string',
            'interview_instructions' => 'required|string',
            'user_id' => 'required|int', // Add validation for user_id
        ]);

        // Find the applicant by applicant_id
        $applicant = ApplicantDetails::find($request->applicant_id);

        // If applicant is not found, return an error
        if (!$applicant) {
            return back()->with('error', 'Applicant not found.');
        }

        // Create the interview schedule, including user_id for the interviewer
        InterviewSchedules::create([
            'applicant_id' => $applicant->id,
            'user_id' => $request->user_id, // Add the user_id to the schedule
            'type' => $request->interview_type,
            'date' => $request->interview_date,
            'time' => $request->interview_time,
            'duration' => $request->interview_duration,
            'location' => $request->interview_location,
            'round' => $request->interview_round,
            'instructions' => $request->interview_instructions, // Added the instructions field as well
        ]);

        // Update the status of the applicant to "Scheduled Interview"
        $applicant->status = 'Scheduled for Interview';
        $applicant->save(); // Save the updated applicant status

        // Send email to applicant
        if ($request->has('sendInvitationApplicant')) {
            Mail::to($request->input('applicant_email'))->send(
                new InterviewInvitation($request->input('interview_instructions'))
            );
        }

        // Send email to interviewer
        if ($request->has('sendInvitationInterviewer')) {
            $user = User::find($request->input('user_id'));
            if ($user) {
                Mail::to($user->email)->send(
                    new InterviewInvitation($request->input('interview_instructions'))
                );
            }
        }

        return redirect()->back()->with('success', 'Interview scheduled successfully.');
    }

    public function edit($id)
    {
        // Retrieve the interview schedule and related models
        $interview = InterviewSchedules::findOrFail($id);
        $applicants = ApplicantDetails::with('jobPosition')
            ->whereIn('status', ['For Interview', 'Completed Interview'])
            ->get();
        $users = User::all();

        return view('interviews.reschedule', compact('interview', 'applicants', 'users'));
    }

    public function update(Request $request, $id)
    {
        // Log request data to check
        Log::info($request->all());
        $request->validate([
            'applicant_id' => 'required|int',
            'interview_type' => 'required|string',
            'interview_date' => 'required|date',
            'interview_time' => 'required|string',
            'interview_duration' => 'required|string',
            'interview_location' => 'required|string',
            'interview_round' => 'required|string',
            'interview_instructions' => 'required|string',
            'user_id' => 'required|int', // Add validation for user_id
        ]);

        // Find the interview schedule by ID
        $interviewSchedule = InterviewSchedules::findOrFail($id);

        // Find the applicant by applicant_id
        $applicant = ApplicantDetails::find($request->applicant_id);

        // If applicant is not found, return an error
        if (!$applicant) {
            return back()->with('error', 'Applicant not found.');
        }

        // Update the interview schedule with the new data
        $interviewSchedule->update([
            'applicant_id' => $applicant->id,
            'user_id' => $request->user_id, // Add the user_id to the schedule
            'type' => $request->interview_type,
            'date' => $request->interview_date,
            'time' => $request->interview_time,
            'duration' => $request->interview_duration,
            'location' => $request->interview_location,
            'round' => $request->interview_round,
            'instructions' => $request->interview_instructions, // Added the instructions field as well
        ]);

        // Optionally update the status of the applicant if necessary
        $applicant->status = 'Scheduled for Interview';
        $applicant->save(); // Save the updated applicant status

        // Send email to applicant
        if ($request->has('sendInvitationApplicant')) {
            Mail::to($request->input('applicant_email'))->send(
                new InterviewInvitation($request->input('interview_instructions'))
            );
        }

        // Send email to interviewer
        if ($request->has('sendInvitationInterviewer')) {
            $user = User::find($request->input('user_id'));
            if ($user) {
                Mail::to($user->email)->send(
                    new InterviewInvitation($request->input('interview_instructions'))
                );
            }
        }

        return redirect()->route('interviews.scheduled')->with('success', 'Interview schedule updated successfully.');
    }

    public function updateOutcome(Request $request, $id)
    {
        $request->validate([
            'interview_outcome' => 'required|in:Pass,Fail,No Decision',
        ]);

        $interview = InterviewSchedules::findOrFail($id);
        $interview->status = $request->input('interview_outcome');
        $interview->save();

        return redirect()->back()->with('success', 'Interview outcome updated successfully.');
    }
    /*  */
}
