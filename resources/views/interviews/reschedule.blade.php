@extends('layouts.app')

@section('title', 'Edit Interview')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Edit Interview Schedule</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <form id="scheduleInterviewForm" action="{{ route('interviews.update', $interview->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Candidate Details -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Candidate Details</legend>

               
                        <div class="form-group">
                            <label for="applicantName">Applicant Name:</label>
                            <input type="text" name="applicant_name" id="applicantName" class="form-control"
                                value="{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}"
                                readonly>
                            <input type="hidden" name="applicant_id" value="{{ $interview->applicant_id }}">

                        </div>

           
                        <div class="form-group">
                            <label for="positionApplied">Position Applied For:</label>
                            <input type="text" name="position_applied" id="positionApplied" class="form-control" required
                                readonly
                                value="{{ old('position_applied') ?? ($interview->applicant->jobPosition->position_title ?? '') }}">
                        </div>

                       
                        <div class="form-group">
                            <label for="applicantEmail">Applicant Email Address:</label>
                            <input type="email" name="applicant_email" id="applicantEmail" class="form-control" required
                                readonly value="{{ old('applicant_email') ?? ($interview->applicant->email ?? '') }}">
                        </div>

                       
                        <div class="form-group">
                            <label for="applicantPhone">Applicant Phone Number:</label>
                            <input type="tel" name="applicant_phone" id="applicantPhone" class="form-control" required
                                readonly value="{{ old('applicant_phone') ?? ($interview->applicant->phone ?? '') }}">
                        </div>
                    </fieldset>

                    <!-- Interview Details -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Interview Details</legend>
                        <div class="form-group">
                            <label for="interviewType">Interview Type:</label>
                            <select name="interview_type" id="interviewType" class="form-control" required>
                                <option value="in-person"
                                    {{ old('interview_type', $interview->type) == 'in-person' ? 'selected' : '' }}>In-person
                                </option>
                                <option value="virtual"
                                    {{ old('interview_type', $interview->type) == 'virtual' ? 'selected' : '' }}>Virtual
                                </option>
                                <option value="phone"
                                    {{ old('interview_type', $interview->type) == 'phone' ? 'selected' : '' }}>Phone
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="interviewDate">Interview Date:</label>
                            <input type="date" name="interview_date" id="interviewDate" class="form-control" required
                                value="{{ old('interview_date', $interview->date) }}">
                        </div>
                        <div class="form-group">
                            <label for="interviewTime">Interview Time:</label>
                            <input type="text" name="interview_time" id="interviewTime" class="form-control" required
                                value="{{ old('interview_time', $interview->time) }}">
                        </div>
                        <div class="form-group">
                            <label for="interviewDuration">Interview Duration:</label>
                            <input type="text" name="interview_duration" id="interviewDuration" class="form-control"
                                placeholder="e.g., 30 minutes" required
                                value="{{ old('interview_duration', $interview->duration) }}">
                        </div>
                        <div class="form-group">
                            <label for="interviewLocation">Interview Location:</label>
                            <input type="text" name="interview_location" id="interviewLocation" class="form-control"
                                placeholder="Specify venue or meeting link" required
                                value="{{ old('interview_location', $interview->location) }}">
                        </div>
                    </fieldset>

                    <!-- Interview Panel -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Interview Panel</legend>
                        
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
                                <option value="Initial Interview"
                                    {{ old('interview_round', $interview->round) == 'Initial Interview' ? 'selected' : '' }}>
                                    Round 1 - Initial Interview</option>
                                <option value="Technical Assessment"
                                    {{ old('interview_round', $interview->round) == 'Technical Assessment' ? 'selected' : '' }}>
                                    Round 2 - Technical Assessment</option>
                                <option value="Management Interview"
                                    {{ old('interview_round', $interview->round) == 'Management Interview' ? 'selected' : '' }}>
                                    Round 3 - Management Interview</option>
                            </select>
                        </div>
                    </fieldset>

                    <!-- Interview Instructions -->
                    <fieldset class="border p-4 mb-4 bg-light">
                        <legend class="w-auto font-weight-bold">Interview Instructions</legend>
                        <div id="editor"></div>
                        <input type="hidden" name="interview_instructions" id="interviewInstructions"
                            value="{{ old('interview_instructions', $interview->instructions) }}">
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
                        <button type="submit" class="btn btn-primary">UPDATE INTERVIEW</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

<p>Thank you for applying to Chimes Consulting!</p>
<p>Your interview has been <strong>rescheduled</strong>. Please see the updated details below:</p>
<p>📅 Date & Time: ${date} at ${time}<br>
📍 Location/Link: <a href="${location}" target="_blank">${location}</a></p>
<p>🔢 Meeting ID: 608 452 5601<br>
🔐 Passcode: chimes</p>
<p>Kindly respond to this email to confirm your attendance. We also request that you join at least 5 minutes before your scheduled time to ensure a prompt start.</p>
<p>Should you have any questions or concerns, feel free to reach out.</p>
<p>We look forward to meeting you!</p>

<p>Best regards,<br>
HR Department<br>
Chimes Consulting</p>
`;

            function updateEmailTemplate() {
                const name = '{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}';
                const date = document.getElementById('interviewDate').value || '[Insert date here]';
                const time = document.getElementById('interviewTime').value || '[Insert time here]';
                const location = document.getElementById('interviewLocation').value ||
                    '[Insert location/link here]';

                quill.root.innerHTML = emailTemplate(name, date, time, location);
            }

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
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
