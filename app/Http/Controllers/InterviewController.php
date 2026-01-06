<?php

namespace App\Http\Controllers;

use App\Models\ApplicantDetails;
use App\Models\InterviewSchedules;
use Illuminate\Http\Request;
use App\Models\InterviewAnswer;
use App\Models\InterviewQuestion;
use App\Models\InterviewSet;
use App\Models\EvaluationForm;
use App\Mail\FeedbackFormRequestMail;
use Illuminate\Support\Facades\Mail;

class InterviewController extends Controller
{

    public function showCompletedInterviews(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by');

        $query = InterviewSchedules::with(['applicant', 'interviewer'])
            ->where('interview_schedules.status', '!=', 'Pending')
            ->when($search, function ($query, $search) {
                $query->whereHas('applicant', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                    });
                });
            })
            ->join('applicant_details', 'interview_schedules.applicant_id', '=', 'applicant_details.id')
            ->select('interview_schedules.*');

        // Apply sorting based on dropdown value
        switch ($sortBy) {
            case 'first_name_asc':
                $query->orderBy('applicant_details.first_name', 'asc');
                break;
            case 'date_asc':
                $query->orderBy('interview_schedules.date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('interview_schedules.date', 'desc');
                break;
            default:
                $query->orderBy('applicant_details.first_name', 'asc');
                break;
        }


        $completedInterviews = $query->get();

        return view('interviews.records', compact('completedInterviews', 'search', 'sortBy'));
    }





    public function showInterviewResult($id)
    {


        $interview = InterviewSchedules::with([
            'applicant',
            'applicant.jobPosition',
            'applicant.education',
            'applicant.internship',
            'applicant.employeeEducation',
            'interviewer',
            'interviewAnswers.interviewQuestion'
        ])->findOrFail($id);
        $applicant = $interview->applicant;

        $groupedAnswers = [];
        foreach ($interview->interviewAnswers as $answer) {
            $type = $answer->interviewQuestion->question_type ?? 'Uncategorized';
            if (!isset($groupedAnswers[$type])) {
                $groupedAnswers[$type] = [];
            }
            $groupedAnswers[$type][] = $answer;
        }
        $interviewAnswers = $interview->interviewAnswers;


        return view('interviews.view', compact('applicant', 'interview', 'groupedAnswers', 'interviewAnswers'));
    }


    public function scheduled()
    {
        $scheduledInterviews = InterviewSchedules::with('applicant')
            ->where('status', 'pending')
            ->orderBy('date', 'asc')
            ->get();

        return view('interviews.scheduled', compact('scheduledInterviews'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(Request $request, $interviewScheduleId)
    {

        $validated = $request->validate([
            'answers' => 'array',
            'answers.*' => 'required|string',
            'overall_impression_summary' => 'required|string',
            'strengths' => 'required|string',
            'areas_for_improvement' => 'required|string',
            'technical_assessment' => 'nullable|string',
            'cultural_fit' => 'required|string',
            'rating_score' => 'nullable|integer|min:1|max:5',
            'expected_salary' => 'nullable|numeric',
            'follow_up_actions' => 'required|string',
            'interview_outcome' => 'required|in:Pass,Fail,No Decision',
            'notes' => 'nullable|string',
        ]);


        foreach ($validated['answers'] as $questionId => $answer) {
            $question = InterviewQuestion::findOrFail($questionId);

            InterviewAnswer::create([
                'interview_question_id' => $question->id,
                'interview_schedule_id' => $interviewScheduleId,
                'answer' => $answer,
            ]);
        }


        EvaluationForm::create([
            'interview_schedule_id' => $interviewScheduleId,
            'overall_impression_summary' => $validated['overall_impression_summary'],
            'strengths' => $validated['strengths'],
            'areas_for_improvement' => $validated['areas_for_improvement'],
            'technical_assessment' => $validated['technical_assessment'],
            'cultural_fit' => $validated['cultural_fit'],
            'rating_score' => $validated['rating_score'],
            'expected_salary' => $validated['expected_salary'],
            'follow_up_actions' => $validated['follow_up_actions'],
            'notes' => $validated['notes'],
        ]);


        $interviewSchedule = InterviewSchedules::findOrFail($interviewScheduleId);
        $applicant = $interviewSchedule->applicant;


        $interviewSchedule->status = $validated['interview_outcome'];
        $interviewSchedule->save();


        $applicant->status = 'Completed Interview';
        $applicant->save();
        Mail::to($applicant->email)->send(new FeedbackFormRequestMail($applicant, $interviewScheduleId));
        return redirect()->route('interviews.completed', $interviewScheduleId)
            ->with('success', 'Interview outcome saved successfully!');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $interview = InterviewSchedules::findOrFail($id);
        $applicant = $interview->applicant()->with('education', 'employeeEducation')->first();
        $interviewSets = InterviewSet::all();
        return view('interviews.conduct', compact('applicant', 'interview', 'interviewSets'));
    }


    public function storeAnswers(Request $request, $interviewScheduleId)
    {
        $validated = $request->validate([
            'answers' => 'array',
            'answers.*' => 'required|string',
        ]);

        foreach ($validated['answers'] as $questionId => $answer) {
            $question = InterviewQuestion::findOrFail($questionId);

            InterviewAnswer::create([
                'interview_question_id' => $question->id,
                'interview_schedule_id' => $interviewScheduleId,
                'answer' => $answer,
            ]);
        }

        return redirect()->route('interview.show', ['id' => $interviewScheduleId])->with('success', 'Interview answers saved successfully!');
    }

    public function fetchQuestions(Request $request)
    {

        $validated = $request->validate([
            'set_ids' => 'required|array',
            'set_ids.*' => 'exists:interview_sets,id'
        ]);


        $questions = InterviewSet::whereIn('id', $validated['set_ids'])
            ->with('questions')
            ->get()
            ->flatMap(function ($set) {
                return $set->questions;
            });


        return response()->json([
            'questions' => $questions,
        ]);
    }

    public function storeEvaluation(Request $request, $interviewScheduleId)
    {
        $request->validate([
            'overall_impression_summary' => 'required|string',
            'strengths' => 'required|string',
            'areas_for_improvement' => 'required|string',
            'technical_assessment' => 'nullable|string',
            'cultural_fit' => 'required|string',
            'rating_score' => 'nullable|integer|min:1|max:5',
            'expected_salary' => 'nullable|numeric',
            'follow_up_actions' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        EvaluationForm::create([
            'interview_schedule_id' => $interviewScheduleId,
            'overall_impression_summary' => $request->overall_impression_summary,
            'strengths' => $request->strengths,
            'areas_for_improvement' => $request->areas_for_improvement,
            'technical_assessment' => $request->technical_assessment,
            'cultural_fit' => $request->cultural_fit,
            'rating_score' => $request->rating_score,
            'expected_salary' => $request->expected_salary,
            'follow_up_actions' => $request->follow_up_actions,
            'notes' => $request->notes
        ]);

        return redirect()->route('interview.show', $interviewScheduleId)->with('success', 'Evaluation saved successfully.');
    }


    public function destroy($id)
    {
        $interview = InterviewSchedules::findOrFail($id);
        $interview->delete();

        return redirect()->back()->with('success', 'Interview schedule deleted successfully.');
    }
}
