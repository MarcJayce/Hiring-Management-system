@extends('layouts.app')

@section('title', 'Conduct Interview')

@section('content')
    <div class="container">
        <h1>Applicant Profile Summary</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold">{{ $applicant->first_name }} {{ $applicant->last_name }}</h1>
                        <h4 class="text-secondary">Applying for {{ $applicant->jobPosition->position_title }}</h4>
                    </div>
                    <!-- Button on the right side of the card -->
                    <a href="{{ url('candidates/' . $applicant->id) }}" class="btn btn-primary">Profile Link</a>
                </div>

                <hr>

                <div>
                    @if ($applicant->jobPosition->application_type == 'Intern')
                        <strong>College/University:</strong>
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
                                    <strong>College/University:</strong> {{ $education->university_name }}<br>
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
        <!-- Interview Details Card -->
        <h2 class="mt-1">Interview Details</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($interview->date)->format('F d, Y') }}<br>
                    <strong>Time:</strong> {{ \Carbon\Carbon::parse($interview->time)->format('h:i A') }}<br>
                    <strong>Duration:</strong> {{ $interview->duration }}<br>
                    <strong>Location:</strong> {{ $interview->location ?? 'N/A' }}<br>
                    <strong>Round:</strong> {{ $interview->round ?? 'N/A' }}<br>
                    <strong>Interviewer:</strong> {{ $interview->interviewer->name }}<br>
                </div>
            </div>
        </div>
        <!-- Interview Questions Section -->
        <h2 class="mt-1">Interview Questions</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('interview.store', $interview->id) }}" method="POST" id="interviewForm">
                    @csrf

                    <h4>Select Interview Sets</h4>

                    @foreach ($interviewSets as $interviewSet)
                        <div class="form-check">
                            <input class="form-check-input interview-set-checkbox" type="checkbox" name="interview_sets[]"
                                value="{{ $interviewSet->id }}" id="interviewSet{{ $interviewSet->id }}">
                            <label class="form-check-label" for="interviewSet{{ $interviewSet->id }}">
                                {{ $interviewSet->name }}
                            </label>
                        </div>
                    @endforeach

                    <button type="button" id="fetchQuestionsBtn" class="btn btn-info mt-3">Fetch Questions</button>

                    <div id="questionsContainer" class="mt-3">
                        <!-- The questions will be dynamically loaded here -->
                    </div>

                    <h2 class="mt-1">Evaluation Form</h2>

                    <!-- Other form fields -->

                    <div class="mb-3">
                        <label for="overall_impression_summary" class="form-label">Overall Impression Summary</label>
                        <textarea name="overall_impression_summary" id="overall_impression_summary" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="strengths" class="form-label">Strengths</label>
                        <textarea name="strengths" id="strengths" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="areas_for_improvement" class="form-label">Areas for Improvement</label>
                        <textarea name="areas_for_improvement" id="areas_for_improvement" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="technical_assessment" class="form-label">Technical Assessment</label>
                        <textarea name="technical_assessment" id="technical_assessment" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cultural_fit" class="form-label">Cultural Fit</label>
                        <textarea name="cultural_fit" id="cultural_fit" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="rating_score" class="form-label">Rating Score</label>
                        <input type="number" name="rating_score" id="rating_score" class="form-control" min="1"
                            max="5" />
                    </div>

                    <div class="mb-3">
                        <label for="expected_salary" class="form-label">Expected Salary</label>
                        <input type="number" name="expected_salary" id="expected_salary" class="form-control" />
                    </div>

                    <div class="mb-3">
                        <label for="follow_up_actions" class="form-label">Follow-up Actions</label>
                        <textarea name="follow_up_actions" id="follow_up_actions" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Interview Outcome Buttons -->
                    <div class="mb-3">
                        <button type="submit" name="interview_outcome" value="Pass" class="btn btn-success">Pass</button>
                        <button type="submit" name="interview_outcome" value="Fail" class="btn btn-danger">Fail</button>
                        <button type="submit" name="interview_outcome" value="No Decision"
                            class="btn btn-warning">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('fetchQuestionsBtn').addEventListener('click', function() {
            const selectedSets = [];
            const checkboxes = document.querySelectorAll('.interview-set-checkbox:checked');

            // Collect selected interview sets
            checkboxes.forEach(function(checkbox) {
                selectedSets.push(checkbox.value);
            });

            // If no sets are selected, alert the user
            if (selectedSets.length === 0) {
                alert('Please select at least one interview set.');
                return;
            }

            // Fetch the questions based on selected sets
            fetch('{{ route('interview.fetchQuestions') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        set_ids: selectedSets
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Clear the previous questions container
                    const questionsContainer = document.getElementById('questionsContainer');
                    questionsContainer.innerHTML = '';

                    // Group questions by their type (e.g., Behavioral or Technical)
                    const groupedQuestions = data.questions.reduce((groups, question) => {
                        const questionType = question.question_type ||
                        'Uncategorized'; // Default if type is missing
                        if (!groups[questionType]) {
                            groups[questionType] = [];
                        }
                        groups[questionType].push(question);
                        return groups;
                    }, {});

                    // Loop through the groups and display them
                    for (const [type, questions] of Object.entries(groupedQuestions)) {
                        // Create a header for each question type
                        const typeHeader = document.createElement('h5');
                        typeHeader.textContent =
                        type; // Display the question type (e.g., Behavioral, Technical)
                        questionsContainer.appendChild(typeHeader);

                        // Loop through each question in the group
                        questions.forEach(function(question) {
                            const questionDiv = document.createElement('div');
                            questionDiv.classList.add('mb-3');

                            const label = document.createElement('label');
                            label.innerHTML =
                                `<strong>${question.question_text} <span style="color: red;">*</span></strong>`;

                            const textarea = document.createElement('textarea');
                            textarea.classList.add('form-control');
                            textarea.rows = 1;
                            textarea.name = `answers[${question.id}]`;

                            questionDiv.appendChild(label);
                            questionDiv.appendChild(textarea);

                            questionsContainer.appendChild(questionDiv);
                        });

                    }
                })
                .catch(error => {
                    console.error('Error fetching questions:', error);
                });
        });
    </script>


@endsection
