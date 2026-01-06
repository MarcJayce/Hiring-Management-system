<?php

namespace App\Http\Controllers\Apply;

use Illuminate\Http\Request;
use App\Models\ApplicantDetails;
use App\Models\OtherInformation;
use App\Models\ProfessionalExperience;
use App\Models\SkillsAbilities;
use App\Models\EmployeeEducation;
use App\Models\JobSpecifics;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\JobPosition;
use App\Rules\ReCaptcha;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantAcknowledgementMail;
class EmployeeController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $job = $id ? JobPosition::find($id) : null;
        return view('application.employee', compact('job'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Saving Employee Data:', $request->all());
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:applicant_details,email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'position_id' => 'nullable|exists:job_positions,id',
                'desired_salary' => 'nullable|numeric',
                'available_date' => 'required|date',
                'job_interest' => 'required|string',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'linkedin' => 'nullable|url',
                'portfolio_url' => 'nullable|url',
                'referral_source' => 'required|string',
                'why_hire' => 'required|string',
                'professional_experience' => 'required|array',
                'professional_experience.*.company_name' => 'required|string',
                'professional_experience.*.job_title' => 'required|string',
                'professional_experience.*.start_date' => 'required|date',
                'professional_experience.*.end_date' => 'nullable|date',
                'professional_experience.*.responsibilities' => 'required|string',
                'education' => 'required|array',
                'education.*.degree_earned' => 'required|string',
                'education.*.university_name' => 'required|string',
                'education.*.graduation_date' => 'required|date',
                'certifications' => 'nullable|string',
                'technical_skills' => 'nullable|array',
                'technical_skills.*' => 'string',
                'industry_knowledge' => 'nullable|string',
                'soft_skills' => 'nullable|array',
                'soft_skills.*' => 'string',
                'g-recaptcha-response' => ['required', new Recaptcha],
            ]);
        } catch (Exception $e) {
            Log::error('Validation failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }

        DB::beginTransaction();
        try {
            $resumePath = $request->file('resume')->store('resumes', 'public');

            $employee = new ApplicantDetails();
            $employee->fill($validatedData);
            $employee->status = 'For Screening';
            $employee->certifications = $validatedData['certifications'] ?? null;
            $employee->save();

            foreach ($validatedData['professional_experience'] as $experience) {
                ProfessionalExperience::create(array_merge($experience, ['applicant_id' => $employee->id]));
            }

            foreach ($validatedData['education'] as $education) {
                EmployeeEducation::create(array_merge($education, ['applicant_id' => $employee->id]));
            }

            SkillsAbilities::create([
                'applicant_id' => $employee->id,
                'technical_skills' => json_encode($validatedData['technical_skills'] ?? []),
                'industry_knowledge' => $validatedData['industry_knowledge'],
                'soft_skills' => json_encode($validatedData['soft_skills'] ?? []),
            ]);

            JobSpecifics::create([
                'applicant_id' => $employee->id,
                'desired_salary' => $validatedData['desired_salary'],
                'available_date' => $validatedData['available_date'],
                'job_interest' => $validatedData['job_interest'],
                'why_hire' => $validatedData['why_hire'],
            ]);

            OtherInformation::create([
                'applicant_id' => $employee->id,
                'resume' => $resumePath,
                'linkedin' => $validatedData['linkedin'],
                'referral_source' => $validatedData['referral_source'],
            ]);
            $employee->load('jobPosition');
            Mail::to($employee->email)->send(new ApplicantAcknowledgementMail($employee));

            DB::commit();
            return redirect('/thank-you')->with('success', 'Employee application submitted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error storing employee data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
