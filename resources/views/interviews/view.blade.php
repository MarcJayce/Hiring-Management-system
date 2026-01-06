@extends('layouts.app')

@section('title', 'Interview Records')

@section('content')
    <div class="container mt-1">
        <h1>Applicant Profile Summary</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold">{{ $applicant->first_name }} {{ $applicant->last_name }}</h1>
                        <h4 class="text-secondary">Applying for {{ $applicant->jobPosition->position_title }}</h4>
                    </div>
                    <!-- Buttons on the right side of the card -->
                    <div>
                        <a href="{{ route('sidebar.candidates') }}?step=completed_interview"
                            class="btn btn-secondary mr-2">Back</a>
                        <a href="{{ url('candidates/' . $applicant->id) }}" class="btn btn-primary">Profile Link</a>
                    </div>
                </div>

                <hr>

                <div>
                    <strong>College/University:</strong>
                    @if ($applicant->jobPosition->application_type == 'Intern')
                        @if ($applicant->education)
                            {{ $applicant->education->university ?? 'N/A' }}<br>
                            <strong>Major/Minor:</strong>
                            {{ $applicant->education->major_minor ?? 'N/A' }}
                            <br><strong>Desired Start Date:</strong>
                            {{ \Carbon\Carbon::parse($applicant->internship->desired_start_date)->format('F d, Y') }}
                            <br><strong>Desired End Date:</strong>
                            {{ \Carbon\Carbon::parse($applicant->internship->desired_end_date)->format('F d, Y') }}
                        @else
                            <p>No intern education found.</p>
                        @endif
                    @else
                        @if ($applicant->employeeEducation && $applicant->employeeEducation->isNotEmpty())
                            @foreach ($applicant->employeeEducation as $education)
                                <div>
                                    <strong>University:</strong> {{ $education->university_name }}<br>
                                    <strong>Degree Earned:</strong> {{ $education->degree_earned ?? 'N/A' }}
                                </div>
                            @endforeach
                        @else
                            <p>No educational records found.</p>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        <h2 class="mt-4">Interview Details</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($interview->date)->format('F d, Y') }}<br>
                <strong>Time:</strong> {{ \Carbon\Carbon::parse($interview->time)->format('h:i A') }}<br>
                <strong>Duration:</strong> {{ $interview->duration }}<br>
                <strong>Location:</strong> {{ $interview->location ?? 'N/A' }}<br>
                <strong>Round:</strong> {{ $interview->round ?? 'N/A' }}<br>
                <strong>Interviewer:</strong> {{ $interview->interviewer->name }}<br>
            </div>
        </div>

        <h2 class="mt-4">Interview Questions & Answers</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                @if ($interviewAnswers && $interviewAnswers->isNotEmpty())
                    @php
                        $groupedAnswers = $interviewAnswers->groupBy('question.question_type');
                    @endphp

                    @foreach ($groupedAnswers as $type => $answers)
                        <h5 class="mt-3">{{ $type ?? 'Uncategorized' }}</h5>
                        @foreach ($answers as $answer)
                            <div class="mb-3">
                                <strong>{{ $answer->interviewQuestion->question_text }}</strong>
                                <p class="form-control">{{ $answer->answer }}</p>
                            </div>
                        @endforeach
                    @endforeach
                @else
                    <p>No interview answers available.</p>
                @endif
            </div>
        </div>

        <h2 class="mt-4">Evaluation Summary</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <strong>Overall Impression Summary:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->overall_impression_summary ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Strengths:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->strengths ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Areas for Improvement:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->areas_for_improvement ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Technical Assessment:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->technical_assessment ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Cultural Fit:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->cultural_fit ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Rating Score:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->rating_score ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Expected Salary:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->expected_salary ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Follow-up Actions:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->follow_up_actions ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Additional Notes:</strong>
                    <p class="form-control">{{ $interview->evaluationForm->notes ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Interview Outcome:</strong>
                    <p class="form-control">{{ $interview->status ?? 'N/A' }}</p>
                </div>
                <div class="mb-3 text-center">
                    <form id="outcomeForm" action="{{ route('interview.updateOutcome', $interview->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="interview_outcome" id="interviewOutcome">
                        <button type="button" class="btn btn-success mx-2" onclick="confirmOutcome('Pass')">Pass</button>
                        <button type="button" class="btn btn-danger mx-2" onclick="confirmOutcome('Fail')">Fail</button>
                        <button type="button" class="btn btn-warning mx-2"
                            onclick="confirmOutcome('No Decision')">Save</button>
                    </form>

                </div>

            </div>
        </div>

    </div>
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    @endif
    <script>
        function confirmOutcome(outcome) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Change outcome to '" + outcome + "'?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('interviewOutcome').value = outcome;
                    document.getElementById('outcomeForm').submit();
                }
            });
        }
    </script>
@endsection
