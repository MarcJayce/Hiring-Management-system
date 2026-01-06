<?php

namespace App\Http\Controllers\Apply;

use App\Models\ApplicantDetails;
use App\Models\Education;
use App\Models\InternshipSpecifics;
use App\Models\OtherInformation;
use App\Models\SkillsExperiences;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\JobPosition;
use App\Models\AvailabilityDate;
use App\Rules\ReCaptcha;
use App\Mail\ApplicantAcknowledgementMail;
use Illuminate\Support\Facades\Mail;
class InternController
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
        return view('application.intern', compact('job'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Saving Data:', $request->all());

        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:applicant_details,email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'certifications' => 'nullable|string',
                'position_id' => 'nullable|exists:job_positions,id',
                'university' => 'required|string',
                'major_minor' => 'required|string',
                'expected_graduation_date' => 'required|date',
                'academic_projects' => 'required|string',
                'internship_type' => 'required|string',
                'desired_start_date' => 'required|date',
                'hours_required' => 'required|integer',
                'weekly_availability' => 'required|string',
                'internship_goals' => 'required|string',
                'internship_interest' => 'required|string',
                'why_hire' => 'required|string',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'linkedin' => 'nullable|url',
                'referral_source' => 'required|string',
                'skills' => 'nullable|array',
                'skills.*' => 'string',
                'volunteer_experience' => 'nullable|string',
                'part_time_jobs' => 'nullable|string',
                'extracurricular' => 'nullable|string',
                'portfolio_url' => 'nullable|url',
                'interview_availability_1' => 'string',
                'interview_availability_2' => 'string',
                'interview_availability_3' => 'string',
                'g-recaptcha-response' => ['required', new Recaptcha],
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public');
            } else {
                throw new Exception("Resume file not found.");
            }

            Log::info('Resume uploaded:', ['path' => $resumePath]);
            $applicant = new ApplicantDetails();
            $applicant->first_name = $validatedData['first_name'];
            $applicant->last_name = $validatedData['last_name'];
            $applicant->email = $validatedData['email'];
            $applicant->phone = $validatedData['phone'];
            $applicant->address = $validatedData['address'];
            $applicant->city = $validatedData['city'];
            $applicant->position_id = $validatedData['position_id'];
            $applicant->certifications = $validatedData['certifications'] ?? null;
            $applicant->status = 'For Screening';

            if (!$applicant->save()) {
                throw new Exception("Applicant record not saved.");
            }

            Log::info('Applicant Created:', ['id' => $applicant->id]);

            Education::create([
                'applicant_id' => $applicant->id,
                'university' => $validatedData['university'],
                'major_minor' => $validatedData['major_minor'],
                'expected_graduation_date' => $validatedData['expected_graduation_date'],
                'academic_projects' => $validatedData['academic_projects'],
            ]);
            Log::info('Education Created');

            InternshipSpecifics::create([
                'applicant_id' => $applicant->id,
                'internship_type' => $validatedData['internship_type'],
                'desired_start_date' => $validatedData['desired_start_date'],
                'hours_required' => $validatedData['hours_required'],
                'weekly_availability' => $validatedData['weekly_availability'],
                'internship_goals' => $validatedData['internship_goals'],
                'internship_interest' => $validatedData['internship_interest'],
                'why_hire' => $validatedData['why_hire'],
            ]);
            Log::info('InternshipSpecifics Created');

            SkillsExperiences::create([
                'applicant_id' => $applicant->id,
                'skills' => json_encode($validatedData['skills']),
                'volunteer_experience' => $validatedData['volunteer_experience'],
                'part_time_jobs' => $validatedData['part_time_jobs'],
                'extracurricular' => $validatedData['extracurricular'],
                'portfolio_url' => $validatedData['portfolio_url'],
            ]);
            Log::info('SkillsExperiences Created');

            OtherInformation::create([
                'applicant_id' => $applicant->id,
                'resume' => $resumePath,
                'linkedin' => $validatedData['linkedin'],
                'referral_source' => $validatedData['referral_source'],
            ]);
            Log::info('OtherInformation Created');

            AvailabilityDate::create([
                'applicant_id' => $applicant->id,
                'available_date' => $validatedData['interview_availability_1'],
            ]);

            AvailabilityDate::create([
                'applicant_id' => $applicant->id,
                'available_date' => $validatedData['interview_availability_2'],
            ]);

            AvailabilityDate::create([
                'applicant_id' => $applicant->id,
                'available_date' => $validatedData['interview_availability_3'],
            ]);

            Log::info('Availability Dates Created');
            
            $applicant->load('jobPosition');
            Mail::to($applicant->email)->send(new ApplicantAcknowledgementMail($applicant));
            DB::commit();

            return redirect('/thank-you')->with('success', 'Application submitted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error storing applicant data: ' . $e->getMessage());
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
