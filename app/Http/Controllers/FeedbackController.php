<?php

namespace App\Http\Controllers;

use App\Models\Feedback;

use Illuminate\Http\Request;
use App\Models\InterviewSchedules;
use App\Exports\FeedbacksExport;
use Maatwebsite\Excel\Facades\Excel;
class FeedbackController extends Controller
{


    public function export()
    {
        return Excel::download(new FeedbacksExport, 'feedbacks.xlsx');
    }
    /**
     * Display a listing of feedback.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $feedbacks = Feedback::with('interview.interviewer')
            ->orderBy('feedback_date', 'desc')
            ->get();

        return view('feedback.index', compact('feedbacks'));
    }



    /**
     * Display the feedback form.
     *
     * @return \Illuminate\View\View
     */
    public function create($id)
    {
        $interviewSchedule = InterviewSchedules::findOrFail($id);

        // Check if feedback already exists for this interview
        $existingFeedback = Feedback::where('interview_id', $id)->first();

        if ($existingFeedback) {
            return redirect()->route('welcome')->with('feedback_exists', 'Feedback has already been submitted for this interview.');
        }


        return view('feedback.create', [
            'interview_id' => $id,
            'interviewSchedule' => $interviewSchedule
        ]);
    }



    /**
     * Store the submitted feedback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'interview_id' => 'required|exists:interview_schedules,id',
            'feedback_date' => 'required|date',
            'feedback_name' => 'required|string',
            'feedback_text' => 'required|string'
        ]);

        Feedback::create($validated);

        return response()->json(['message' => 'Feedback submitted successfully.']);;
    }


    /**
     * Display feedback for a candidate.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $feedback = Feedback::all();
        return view('feedback.show', compact('feedback'));
    }
}
