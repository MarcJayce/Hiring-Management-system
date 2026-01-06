<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosition;

class JobController
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //
    }

    public function interns()
    {
        $jobPositions = JobPosition::where('application_type', 'Intern')->paginate(10);
        return view('job_positions.interns', compact('jobPositions'));
    }

    public function employees()
    {
        $jobPositions = JobPosition::whereIn('application_type', ['Full-Time Employee', 'Part-Time Employee'])->paginate(10);
        return view('job_positions.employees', compact('jobPositions'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job_positions.add-position');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'work_setup' => 'required|string',
            'reports_to' => 'required|string',
            'job_duration' => 'required|string',
            'work_hours' => 'required|string',
            'compensation' => 'required|string',
            'position_description' => 'required|string',
            'key_responsibilities' => 'required|string',
            'benefits' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'availability' => 'required|integer',
            'status' => 'required|string',
            'application_type' => 'required|string'
        ]);

        JobPosition::create($validated);

        return redirect()->back()->with('success', 'Job position added successfully!');
    }

    public function availablePositions()
    {
        $jobPositions = JobPosition::where('status', 'Active')->get();
        return view('job_positions.view', compact('jobPositions'));
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobPosition = JobPosition::findOrFail($id);
        return view('job_positions.show', compact('jobPosition'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $job = JobPosition::findOrFail($id);
        return view('job_positions.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $job = JobPosition::findOrFail($id);

        $request->validate([
            'position_title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'work_setup' => 'required|string',
            'job_duration' => 'required|string',
            'reports_to' => 'required|string',
            'work_hours' => 'required|string',
            'compensation' => 'required|string',
            'application_type' => 'required|string',
            'position_description' => 'required|string',
            'key_responsibilities' => 'required|string',
            'benefits' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'availability' => 'required|integer|min:1',
            'status' => 'required|string',
        ]);

        $job->update($request->all());

        return redirect()->back()->with('success', 'Job Position updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jobPosition = JobPosition::findOrFail($id);
        $jobPosition->delete();

        return redirect()->route('vacancies.employees')->with('success', 'Job position deleted successfully.');
    }
}
