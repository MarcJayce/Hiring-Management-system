@php
    $statusColors = [
        'For Screening' => '#6c757d',
        'Shortlisted' => '#0d6efd',
        'For Interview' => '#20c997',
        'Scheduled for Interview' => '#0dcaf0',
        'Completed' => '#6610f2',
        'Offered' => '#ffc107',
        'Hired' => '#198754',
        'Rejected' => '#dc3545',
    ];

    $statusColor = $statusColors[$candidate->status] ?? '#6c757d'; // default gray
@endphp

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1><strong>Position:</strong> {{ $candidate->jobPosition->position_title ?? 'Not specified' }}</h1>
            <div class="status-display"
                style="background-color: {{ $statusColor }}; color: #fff; padding: 10px; font-size: 1rem; border-radius: 10px; text-align: center;">
                {{ $candidate->status }}
            </div>
        </div>
        <div class="card p-4"
            style="background-color: #f8f9fa; border-radius: 15px; border: 1px solid #dee2e6; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);margin-bottom: 25px;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 style="font-size: 2rem; color: #212529; font-weight: bold;">{{ $candidate->first_name }}
                        {{ $candidate->last_name }}
                    </h1>
                    <p style="color: #212529; line-height: 1.5;">
                        <i class="fas fa-envelope"></i> {{ $candidate->email }}<br>
                        <i class="fas fa-phone"></i> {{ $candidate->phone }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ $candidate->address }}, {{ $candidate->city }}
                    </p>
                    <p><strong>Application Type:</strong> {{ $candidate->type }}</p>

                </div>

            </div>
        </div>
        <div class="card p-4"
            style="background-color: #f8f9fa; border-radius: 15px; border: 1px solid #dee2e6; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

            <h2 style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Education</h2>

            @if ($candidate->type == 'Intern')
                <ul style="color: #212529;">
                    <li><strong style="color: #212529;">University/College Name:</strong>
                        {{ $candidate->education->university ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Major/Minor:</strong>
                        {{ $candidate->education->major_minor ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Expected Graduation Date:</strong>
                        {{ $candidate->education->expected_graduation_date ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Academic Projects:</strong>
                        {{ $candidate->education->academic_projects ?? 'Not provided' }}
                    </li>
                </ul>
            @elseif($candidate->type == 'Employee')
                @if ($candidate->employeeEducation && $candidate->employeeEducation->isNotEmpty())
                    <ul style="color: #212529;">
                        @foreach ($candidate->employeeEducation as $education)
                            <div style="margin-bottom: 1rem;">
                                <li><strong style="color: #212529;">University/College Name:</strong>
                                    {{ $education->university_name ?? 'Not provided' }}
                                </li>
                                <li><strong style="color: #212529;">Degree:</strong>
                                    {{ $education->degree_earned ?? 'Not provided' }}
                                </li>
                                <li><strong style="color: #212529;">Graduation Date:</strong>
                                    {{ $education->graduation_date ?? 'Not provided' }}
                                </li>
                            </div>
                        @endforeach
                    </ul>
                @endif
            @endif


            @if ($candidate->type == 'Intern')
                <h2 class="mt-4" style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Internship
                    Specifics</h2>
                <ul style="color: #212529;">
                    <li><strong style="color: #212529;">Voluntary Internship or Academic Related:</strong>
                        {{ $candidate->internship->internship_type ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Desired Internship Start Date:</strong>
                        {{ $candidate->internship->desired_start_date ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Number of hours required:</strong>
                        {{ $candidate->internship->hours_required ?? 'Not provided' }} hrs
                    </li>
                    <li><strong style="color: #212529;">Weekly Availability:</strong>
                        {{ $candidate->internship->weekly_availability ?? 'Not provided' }} hrs
                    </li>
                    <li><strong style="color: #212529;">Goals:</strong><br>
                        {{ $candidate->internship->internship_goals ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Why are you interested in this internship?</strong><br>
                        {{ $candidate->internship->internship_interest ?? 'Not provided' }}
                    </li>
                </ul>
                <h2 class="mt-4" style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Skills and
                    Experience</h2>
                <ul style="color: #212529;">
                    <li><strong style="color: #212529;">Relevant Skills:</strong></li>
                    <ul>
                        @foreach (json_decode($candidate->skillsExperience->skills ?? '[]', true) as $skill)
                            <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                    <li><strong style="color: #212529;">Volunteer Experience:</strong>
                        {{ $candidate->skillsExperience->volunteer_experience ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Part-Time/Temporary Jobs:</strong>
                        {{ $candidate->skillsExperience->part_time_jobs ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Extracurricular Activities/Clubs:</strong>
                        {{ $candidate->skillsExperience->extracurricular ?? 'Not provided' }}
                    </li>
                </ul>
                <h2 class="mt-4" style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Questions
                </h2>
                <ul style="color: #212529;">
                    <li><strong style="color: #212529;">Why should we hire you?</strong><br>
                        {{ $candidate->internship->why_hire ?? 'Not provided' }}
                    </li>
                </ul>
                <ul style="color: #212529;">
                    <li><strong style="color: #212529;">How did you hear about this internship?</strong><br>
                        {{ $candidate->otherInfo->referral_source ?? 'Not provided' }}
                    </li>
                </ul>
            @endif
            @if ($candidate->type == 'Employee')
                <h2 class="mt-4" style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Skills and
                    Experience</h2>
                <ul style="color: #212529;">
                    <li><strong style="color: #212529;">Technical Skills:</strong></li>
                    <ul>
                        @foreach (json_decode($candidate->skillsAbilities->technical_skills ?? '[]', true) as $skill)
                            <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                    <li><strong style="color: #212529;">Industry Knowledge:</strong>
                        {{ $candidate->skillsAbilities->industry_knowledge ?? 'Not provided' }}
                    </li>
                    <li><strong style="color: #212529;">Soft Skills:</strong></li>
                    <ul>
                        @foreach (json_decode($candidate->skillsAbilities->soft_skills ?? '[]', true) as $skill)
                            <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                </ul>

                <h2 class="mt-4" style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">
                    Professional Experience</h2>
                <ul style="color: #212529;">
                    @foreach ($candidate->professionalExperience as $experience)
                        <h3><strong style="color: #212529;">{{ $experience->company_name }}</strong></h3>
                        <ul>
                            <li><strong>Job Title:</strong> {{ $experience->job_title }}</li>
                            <li><strong>Start Date:</strong> {{ $experience->start_date }}</li>
                            <li><strong>End Date:</strong> {{ $experience->end_date ?? 'N/A' }}</li>
                            <li><strong>Responsibilities:</strong> {{ $experience->responsibilities }}</li>
                        </ul>
                    @endforeach
                </ul>

                <h2 class="mt-4" style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Job
                    Specifics</h2>
                <ul style="color: #212529;">
                    <ul>
                        <li><strong>Desired Salary:</strong>
                            ₱{{ number_format($candidate->jobSpecifics->desired_salary ?? 0) }}</li>
                        <li><strong>Available Start Date:</strong> {{ $candidate->jobSpecifics->available_date }}</li>
                        <li><strong>Job Interest:</strong> {{ $candidate->jobSpecifics->job_interest }}</li>
                        <li><strong>Why should we hire you?</strong>
                            <p>{{ $candidate->jobSpecifics->why_hire }}</p>
                        </li>
                        <li><strong>References</strong>
                            <p>{{ $candidate->otherInfo->referral_source }}</p>
                        </li>
                    </ul>
                </ul>
            @endif
        </div>

        <div class="card p-4"
            style="background-color: #f8f9fa; border-radius: 15px; border: 1px solid #dee2e6; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 25px;">
            <h2 style="color: #212529; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">Other</h2>
            <ul style="color: #212529;">
                <li><strong style="color: #212529;">Resume/CV Upload:</strong> <a
                        href="{{ asset('storage/' . ($candidate->otherInfo->resume ?? '')) }}" target="_blank">Download
                        CV</a></li>
                <li><strong style="color: #212529;">LinkedIn Profile URL:</strong> <a
                        href="{{ $candidate->otherInfo->linkedin ?? '#' }}"
                        target="_blank">{{ $candidate->otherInfo->linkedin ?? 'Not provided' }}</a>
                </li>
                @if ($candidate->type == 'Intern')
                    <li><strong>Availability Dates:</strong>
                        <ul>
                            @foreach ($candidate->interviewAvailability as $availability)
                                <li>{{ $availability->available_date }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endif

            </ul>
        </div>

        <div class="d-flex justify-content-center mt-4" style="gap: 10px;">
            <button class="btn btn-secondary" onclick="window.history.back()">
                Back to List
            </button>


            @if ($candidate->status === 'For Screening')
                <button class="btn btn-success" onclick="shortlistCandidate({{ $candidate->id }})">
                    Shortlist
                </button>

                <button class="btn btn-info" onclick="moveToForInterview({{ $candidate->id }})">
                    For Interview
                </button>
            @endif


            @if ($candidate->status === 'Shortlisted')
                <button class="btn btn-success" onclick="moveToForInterview({{ $candidate->id }})">
                    For Interview
                </button>
            @endif

            @if ($candidate->status === 'For Interview')
                <button class="btn btn-success"
                    onclick="window.location.href='{{ route('interviews.schedule.withApplicant', ['id' => $candidate->id]) }}'">
                    Schedule Interview
                </button>
            @endif

            @if ($candidate->status === 'Scheduled for Interview')
                @php
                    $latestSchedule = $candidate->interviewSchedules->sortByDesc('created_at')->first();
                @endphp
                <!-- Complete Button -->
                <button class="btn btn-warning btn-sm d-flex align-items-center justify-content-center action-button"
                    style="height: 50px; width: 110px; margin-right: 8px;" data-interview-id="{{ $latestSchedule->id }}"
                    onclick="conductInterview(this)">
                    CONDUCT INTERVIEW
                </button>
            @endif
            @if ($candidate->status === 'Completed Interview')
                <button class="btn btn-success"
                    onclick="window.location.href='{{ route('interviews.completed', ['search' => $candidate->first_name . ' ' . $candidate->last_name]) }}'">
                    View Interviews
                </button>

                <button class="btn btn-info"
                    onclick="window.location.href='{{ route('interviews.schedule.withApplicant', ['id' => $candidate->id]) }}'">
                    Schedule Interview
                </button>

                <button class="btn btn-warning" onclick="openOfferDialog({{ $candidate->id }})">
                    Send Offer
                </button>
            @endif
            @if ($candidate->status === 'Offer Made')
                @if ($candidate->status !== 'Offer Accepted')
                    <button class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                        style="height: 50px; min-width:110px; margin-right: 8px;"
                        onclick="markOfferAccepted({{ $candidate->id }}); return false;">
                        OFFER ACCEPTED
                    </button>
                @endif
            @endif
            @if ($candidate->status === 'Offer Accepted')
                <!-- Hire Button -->
                <button class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                    style="height: 50px; min-width:110px; margin-right: 8px;"
                    onclick="openHireDialog({{ $candidate->id }})">
                    HIRE
                </button>
            @endif

            @if ($candidate->status !== 'Rejected')
                <button class="btn btn-danger" onclick="rejectCandidate({{ $candidate->id }})">
                    Reject
                </button>
            @endif
            @if ($candidate->status === 'Rejected')
                <button class="btn btn-danger"
                    onclick="sendRejectionEmail({{ $candidate->id }}, '{{ $candidate->email }}')"">
                    Send Rejection Email
                </button>
            @endif
        </div>

    </div>
    <script src=" {{ asset('js/candidates.js') }}"></script>
@endsection
