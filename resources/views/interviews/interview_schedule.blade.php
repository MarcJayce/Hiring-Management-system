@extends('layouts.app')

@section('title', 'Schedule Interview')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Schedule Interview Form</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <form id="scheduleInterviewForm" action="{{ route('interviews.schedule') }}" method="POST">
                    @csrf

                    <!-- Candidate Details -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Candidate Details</legend>

                        <!-- Dropdown for selecting applicant -->
                        <div class="form-group">
                            <label for="applicantName">Applicant Name:</label>
                            <select name="applicant_id" id="applicantName" class="form-control" required>
                                <option value="">Select Applicant</option>
                                @foreach ($applicants as $applicant)
                                    <option value="{{ $applicant->id }}"
                                        {{ old('applicant_id') == $applicant->id || (isset($selectedApplicant) && $selectedApplicant->id == $applicant->id) ? 'selected' : '' }}>
                                        {{ $applicant->first_name }} {{ $applicant->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Position Applied -->
                        <div class="form-group">
                            <label for="positionApplied">Position Applied For:</label>
                            <input type="text" name="position_applied" id="positionApplied" class="form-control" required
                                readonly
                                value="{{ old('position_applied') ?? ($selectedApplicant->jobPosition->position_title ?? '') }}">
                        </div>

                        <!-- Applicant Email -->
                        <div class="form-group">
                            <label for="applicantEmail">Applicant Email Address:</label>
                            <input type="email" name="applicant_email" id="applicantEmail" class="form-control" required
                                readonly value="{{ old('applicant_email') ?? ($selectedApplicant->email ?? '') }}">
                        </div>

                        <!-- Applicant Phone Number -->
                        <div class="form-group">
                            <label for="applicantPhone">Applicant Phone Number:</label>
                            <input type="tel" name="applicant_phone" id="applicantPhone" class="form-control" required
                                readonly value="{{ old('applicant_phone') ?? ($selectedApplicant->phone ?? '') }}">
                        </div>
                    </fieldset>

                    <!-- Interview Details -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Interview Details</legend>
                        <div class="form-group">
                            <label for="interviewType">Interview Type:</label>
                            <select name="interview_type" id="interviewType" class="form-control" required>
                                <option value="in-person">In-person</option>
                                <option value="virtual">Virtual</option>
                                <option value="phone">Phone</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="interviewDate">Interview Date:</label>
                            <input type="date" name="interview_date" id="interviewDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="interviewTime">Interview Time:</label>
                            <input type="text" name="interview_time" id="interviewTime" class="form-control"
                                placeholder="1:00 PM" required>
                        </div>
                        <div class="form-group">
                            <label for="interviewDuration">Interview Duration:</label>
                            <input type="text" name="interview_duration" id="interviewDuration" class="form-control"
                                placeholder="e.g., 30 minutes" required>
                        </div>
                        <div class="form-group">
                            <label for="interviewLocation">Interview Location:</label>
                            <input type="text" name="interview_location" id="interviewLocation" class="form-control"
                                placeholder="Specify venue or meeting link" required value="https://us06web.zoom.us/j/6084525601?pwd=ZS9yeHdzRzVCeG5KcHJML3dmWEpZdz09">
                        </div>

                        <small class="form-text text-muted mt-3">
                            Available Dates:
                            <div id="availabilityList">N/A</div>
                        </small>

                    </fieldset>

                    <!-- Interview Panel -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Interview Panel</legend>
                        <!-- Dropdown for selecting applicant -->
                        <div class="form-group">
                            <label for="interviewer_name">Interviewer Name:</label>
                            <select name="user_id" id="interviewerName" class="form-control" required>
                                <option value="">Select Interviewer</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="interviewRound">Interview Round:</label>
                            <select name="interview_round" id="interviewRound" class="form-control" required>
                                <option value="Initial Interview">Round 1 - Initial Interview</option>
                                <option value="Technical Assessment">Round 2 - Technical Assessment</option>
                                <option value="Management Interview">Round 3 - Management Interview</option>
                            </select>
                        </div>
                    </fieldset>

                    <!-- Interview Instructions -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Interview Instructions</legend>
                        <!-- Quill Editor Container -->
                        <div id="editor"></div>
                        <input type="hidden" name="interview_instructions" id="interviewInstructions">
                    </fieldset>


                    <!-- Confirmation & Notifications -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Confirmation & Notifications</legend>
                        <div class="form-group">
                            <label><input type="checkbox" name="sendInvitationApplicant"> Send Invitation to
                                Applicant</label><br>
                            <label><input type="checkbox" name="sendInvitationInterviewer"> Send Invitation to
                                Interviewer</label><br>
                        </div>
                    </fieldset>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">SCHEDULE INTERVIEW</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applicantSelect = document.getElementById('applicantName');

            if (applicantSelect.value && {{ isset($selectedApplicant) ? 'true' : 'false' }}) {
                // If already selected by server, don't fetch again
            } else {
                applicantSelect.addEventListener('change', function() {
                    const applicantId = this.value;

                    if (applicantId) {
                        fetch(`/api/applicants/${applicantId}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('applicantEmail').value = data.email || '';
                                document.getElementById('applicantPhone').value = data.phone || '';
                                document.getElementById('positionApplied').value = data.job_position ?
                                    data.job_position.position_title : '';

                                // Show availability
                                const availabilityContainer = document.getElementById(
                                    'availabilityList');
                                availabilityContainer.innerHTML = ''; // clear previous data

                                if (data.interview_availability && data.interview_availability.length >
                                    0) {
                                    const ul = document.createElement('ul');
                                    ul.style.margin = 0;
                                    ul.style.paddingLeft = '20px';

                                    data.interview_availability.forEach(item => {
                                        const li = document.createElement('li');
                                        li.textContent = item.available_date;
                                        ul.appendChild(li);
                                    });

                                    availabilityContainer.appendChild(ul);
                                } else {
                                    availabilityContainer.textContent = 'N/A';
                                }
                            })
                            .catch(error => console.error('Error fetching applicant:', error));
                    } else {
                        document.getElementById('applicantEmail').value = '';
                        document.getElementById('applicantPhone').value = '';
                        document.getElementById('positionApplied').value = '';
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const applicantSelect = document.getElementById('applicantName');
            const editor = document.getElementById('editor');

            const quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Enter the interview instructions...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            const form = document.getElementById('scheduleInterviewForm');
            const instructionsInput = document.getElementById('interviewInstructions');

            const emailTemplate = (name = 'Applicant', date = '[Insert date here]', time = '[Insert time here]',
                location = '[Insert location/link here]') => `
<p>Dear ${name},</p>

Your virtual interview zoom details are as follows:
<p>📅 Date & Time: ${date} at ${time}<br></p>
<p>📍 Location/Link: <a href="${location}" target="_blank">${location}</a></p>
<p>🔢 Meeting ID: 608 452 5601<br></p>
<p>🔐 Passcode: chimes</p>
<p>Please respond to the calendar invite to confirm your attendance. Additionally, ensure you are in the Zoom room 5 minutes before the scheduled interview time.</p>
<p>Thank you!</p>
`;

            function updateEmailTemplate() {
                const selectedOption = applicantSelect.options[applicantSelect.selectedIndex];
                const name = selectedOption.value ? selectedOption.text : 'Applicant';
                const date = document.getElementById('interviewDate').value || '[Insert date here]';
                const time = document.getElementById('interviewTime').value || '[Insert time here]';
                const location = document.getElementById('interviewLocation').value ||
                    '[Insert location/link here]';

                quill.root.innerHTML = emailTemplate(name, date, time, location);
            }


            applicantSelect.addEventListener('change', updateEmailTemplate);
            document.getElementById('interviewDate').addEventListener('input', updateEmailTemplate);
            document.getElementById('interviewTime').addEventListener('input', updateEmailTemplate);
            document.getElementById('interviewLocation').addEventListener('input', updateEmailTemplate);

            form.addEventListener('submit', function() {
                instructionsInput.value = quill.root.innerHTML;
            });

            // Set initial content
            updateEmailTemplate();
        });
    </script>
@endsection
