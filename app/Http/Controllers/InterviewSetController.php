<?php

namespace App\Http\Controllers;

use App\Models\InterviewSet;
use Illuminate\Http\Request;
use App\Models\InterviewQuestion;
class InterviewSetController extends Controller
{
    public function index()
    {
        $interviewSets = InterviewSet::all();
        $behavioralQuestions = \App\Models\InterviewQuestion::where('question_type', 'Behavioral')->get()->groupBy('set_id');
        $technicalQuestions = \App\Models\InterviewQuestion::where('question_type', 'Technical')->get()->groupBy('set_id');

        return view('interviews.interview_questions', compact('interviewSets', 'behavioralQuestions', 'technicalQuestions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'set_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        // Create a new interview set
        InterviewSet::create([
            'name' => $request->set_name,
            'category' => $request->category,
        ]);

        return redirect()->route('interviews.sets.index')->with('success', 'New set added successfully.');
    }

    public function destroy($id)
    {
        $interviewSet = InterviewSet::findOrFail($id);
        $interviewSet->delete();

        return redirect()->route('interviews.sets.index')
            ->with('success', 'Interview set deleted successfully');
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:255',
            'question_type' => 'required|in:Behavioral,Technical',
            'set_id' => 'required|exists:interview_sets,id'
        ]);

        \App\Models\InterviewQuestion::create([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'set_id' => $request->set_id
        ]);

        return redirect()->back()->with('success', 'Question added successfully');
    }

    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string|max:255',
            'question_type' => 'required|in:Behavioral,Technical'
        ]);

        $question = \App\Models\InterviewQuestion::findOrFail($id);
        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type
        ]);

        return redirect()->back()->with('success', 'Question updated successfully');
    }

    public function destroyQuestion($id)
    {
        $question = \App\Models\InterviewQuestion::findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:Behavioral,Technical',
        ]);

        $question = InterviewQuestion::findOrFail($id);
        $question->question_text = $request->question_text;
        $question->question_type = $request->question_type;
        $question->save();

        return redirect()->back()->with('success', 'Question updated successfully.');
    }
}
