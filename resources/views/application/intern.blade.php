@php
    $layout = Auth::check() ? 'layouts.app' : 'layouts.guest';
@endphp

@extends($layout)

@section('title', 'Intern Application')

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <h1>Applying for {{ $job ? $job->position_title : '[Position Placeholder]' }}</h1>
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 900px;">
            <h2 class="text-center mb-4">Application Form</h2>
            <form id="internApplicationForm" action="{{ route('intern.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="position_id" value="{{ $job->id ?? '' }}">
                <h5 class="mb-3 font-weight-bold">Personal Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email Address<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Address<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="address"
                        placeholder="Street Address, State/Province, Zip/Postal Code" required>
                </div>

                <div class="form-group">
                    <label>City<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="city" required>
                </div>

                <h5 class="mt-4 mb-3 font-weight-bold">Education</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>University/Institution<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="university" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Major/Minor<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="major_minor" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Expected Graduation Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="expected_graduation_date" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Academic Projects<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="academic_projects" rows="3" required></textarea>
                </div>

                <h5 class="mt-4 mb-3 font-weight-bold">Internship Specifics</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Internship Type<span class="text-danger">*</span></label>
                            <select class="form-control" name="internship_type" required>
                                <option value="voluntary">Voluntary</option>
                                <option value="academic">Academic Related</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Number of Hours Required<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="hours_required" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Desired Start Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="desired_start_date" required>
                        </div>
                    </div>

                    <!--<div class="col-md-6">
                                <div class="form-group">
                                    <label>Desired End Date<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="desired_end_date" required>
                                </div>
                            </div>
                        -->
                </div>

                <div class="form-group">
                    <label>Weekly Availability (hours)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="weekly_availability" required>
                </div>

                <div class="form-group">
                    <label>Internship Goals<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="internship_goals" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Why are you interested in this internship?<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="internship_interest" rows="3" required></textarea>
                </div>

                <h5 class="mt-4 mb-3 font-weight-bold">Skills and Experience</h5>

                <div class="form-group">
                    <label>Relevant Skills (Select all that apply)<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-6">
                            <label><input type="checkbox" name="skills[]" value="Content Creation"> Content
                                Creation</label><br>
                            <label><input type="checkbox" name="skills[]" value="SEO & SEM"> SEO & SEM</label><br>
                            <label><input type="checkbox" name="skills[]" value="Marketing Research and Analysis">
                                Marketing Research and Analysis</label><br>
                            <label><input type="checkbox" name="skills[]" value="Brand Management"> Brand
                                Management</label><br>
                        </div>
                        <div class="col-md-6">
                            <label><input type="checkbox" name="skills[]" value="Web Development"> Web
                                Development</label><br>
                            <label><input type="checkbox" name="skills[]" value="Database Management"> Database
                                Management</label><br>
                            <label><input type="checkbox" name="skills[]" value="Software Development"> Software
                                Development</label><br>
                            <label><input type="checkbox" name="skills[]" value="UI/UX Design"> UI/UX Design</label><br>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label>Volunteer Experience (Optional)</label>
                    <textarea class="form-control" name="volunteer_experience" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Part-Time/Temporary Jobs (Optional)</label>
                    <textarea class="form-control" name="part_time_jobs" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Extra-Curricular Activities (Optional)</label>
                    <textarea class="form-control" name="extracurricular" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Portfolio/Website URL (Optional)</label>
                    <input type="url" class="form-control" name="portfolio_url">
                </div>

                <h5 class="mt-4 mb-3 font-weight-bold">Other Information</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Upload Resume/CV<span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file" name="resume" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>LinkedIn Profile (Optional)</label>
                            <input type="url" class="form-control" name="linkedin">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>How did you hear about this internship?<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="referral_source" required>
                </div>

                <div class="form-group">
                    <label>Why should we hire you?<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="why_hire" rows="3" required></textarea>
                </div>

                <h5 class="mt-4 mb-3 font-weight-bold">Interview Availability</h5>

                <div class="form-group">
                    <label>Availability Date 1<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="interview_availability_1"
                        placeholder="e.g., June 1, 2025 - 9:00 AM - 3:00PM" required>
                </div>

                <div class="form-group">
                    <label>Availability Date 2<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="interview_availability_2"
                        placeholder="e.g., June 3, 2025 - 9:00 AM - 3:00PM" required>
                </div>

                <div class="form-group">
                    <label>Availability Date 3<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="interview_availability_3"
                        placeholder="e.g., June 5, 2025 - 9:00 AM - 3:00PM" required>
                </div>
                <div class="form-group mt-3">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3" id="submitBtn"
                    @if (Auth::check()) disabled @endif>Submit Application</button>
            </form>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let submitBtn = document.getElementById('submitBtn');
            if (!submitBtn) return;

            submitBtn.addEventListener('click', function(event) {
                event.preventDefault();

                let form = document.getElementById('internApplicationForm');
                let errors = [];
                let firstInvalidField = null;

                function checkField(selector, message) {
                    let field = document.querySelector(selector);
                    if (field && !field.value.trim()) {
                        errors.push(message);
                        field.classList.add('is-invalid');
                        if (!firstInvalidField) firstInvalidField = field;
                    } else if (field) {
                        field.classList.remove('is-invalid');
                    }
                }

                function validateURL(selector, message) {
                    let field = document.querySelector(selector);
                    let urlPattern = /^(https?:\/\/)?([\w-]+\.)+[\w-]{2,4}(\/[\w-]*)*\/?$/;

                    if (field && field.value.trim() !== '' && !urlPattern.test(field.value)) {
                        errors.push(message);
                        field.classList.add('is-invalid');
                        if (!firstInvalidField) firstInvalidField = field;
                    } else if (field) {
                        field.classList.remove('is-invalid');
                    }
                }

                // Validate required fields
                checkField('input[name="first_name"]', 'First Name is required.');
                checkField('input[name="last_name"]', 'Last Name is required.');
                checkField('input[name="email"]', 'Email Address is required.');
                checkField('input[name="phone"]', 'Phone Number is required.');
                checkField('input[name="address"]', 'Address is required.');
                checkField('input[name="city"]', 'City is required.');
                checkField('input[name="university"]', 'University/Institution is required.');
                checkField('input[name="major_minor"]', 'Major/Minor is required.');
                checkField('input[name="expected_graduation_date"]',
                    'Expected Graduation Date is required.');
                checkField('textarea[name="academic_projects"]', 'Academic Projects are required.');
                checkField('select[name="internship_type"]', 'Internship Type is required.');
                checkField('input[name="hours_required"]', 'Number of Hours Required is required.');
                checkField('input[name="desired_start_date"]', 'Desired Start Date is required.');
                //checkField('input[name="desired_end_date"]', 'Desired End Date is required.');
                checkField('input[name="weekly_availability"]', 'Weekly Availability is required.');
                checkField('textarea[name="internship_goals"]', 'Internship Goals are required.');
                checkField('textarea[name="internship_interest"]', 'Reason for Internship is required.');
                checkField('input[name="referral_source"]', 'Referral Source is required.');
                checkField('textarea[name="why_hire"]', 'Why Should We Hire You is required.');
                checkField('text[name="interview_availability_1"]', 'Interview Availability is Required');
                checkField('text[name="interview_availability_2"]', 'Interview Availability is Required');
                checkField('text[name="interview_availability_3"]', 'Interview Availability is Required');
                // Validate Resume Upload (Required + Max Size 2MB)
                let resumeInput = document.querySelector('input[name="resume"]');
                if (!resumeInput || !resumeInput.files.length) {
                    errors.push('Resume/CV is required.');
                    if (!firstInvalidField) firstInvalidField = resumeInput;
                } else {
                    let file = resumeInput.files[0];
                    let maxSize = 5 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        errors.push('Resume file size must be 5MB or less.');
                        resumeInput.classList.add('is-invalid');
                        if (!firstInvalidField) firstInvalidField = resumeInput;
                    } else {
                        resumeInput.classList.remove('is-invalid');
                    }
                }

                let allowedTypes = [
                    "application/pdf",
                    "application/msword",
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                ];

                if (!resumeInput || !resumeInput.files.length) {
                    errors.push('Resume/CV is required.');
                    resumeInput.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = resumeInput;
                } else {
                    let file = resumeInput.files[0];
                    let maxSize = 5 * 1024 * 1024; // 2MB

                    if (file.size > maxSize) {
                        errors.push('Resume file size must be 5MB or less.');
                        resumeInput.classList.add('is-invalid');
                        if (!firstInvalidField) firstInvalidField = resumeInput;
                    } else if (!allowedTypes.includes(file.type)) {
                        errors.push("Resume must be a PDF or Word document.");
                        resumeInput.classList.add("is-invalid");
                        if (!firstInvalidField) firstInvalidField = resumeInput;
                    } else {
                        resumeInput.classList.remove("is-invalid");
                    }
                }

                function isValidEmail(email) {
                    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                }

                // Inside the form validation checks:
                let emailInput = document.querySelector('input[name="email"]');
                if (emailInput && !isValidEmail(emailInput.value.trim())) {
                    errors.push('Please enter a valid email address.');
                    emailInput.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = emailInput;
                } else if (emailInput) {
                    emailInput.classList.remove('is-invalid');
                }

                // Validate Portfolio URL (if provided)
                validateURL('input[name="portfolio_url"]', 'Invalid Portfolio/Website URL.');

                // Validate LinkedIn URL (if provided)
                validateURL('input[name="linkedin"]', 'Invalid LinkedIn Profile URL.');

                // Check at least one skill is selected
                let skillChecked = document.querySelectorAll('input[name="skills[]"]:checked').length > 0;
                let skillsContainer = document.querySelector('.skills-container');
                if (!skillChecked) {
                    errors.push('At least one relevant skill must be selected.');
                    if (skillsContainer) {
                        skillsContainer.classList.add('border', 'border-danger', 'p-2');
                    }
                    if (!firstInvalidField) firstInvalidField = skillsContainer;
                } else if (skillsContainer) {
                    skillsContainer.classList.remove('border', 'border-danger', 'p-2');
                }

                // Show validation errors
                if (errors.length > 0) {
                    Swal.fire({
                        title: "Form Validation Error",
                        html: `<ul class="text-left">${errors.map(error => `<li>${error}</li>`).join('')}</ul>`,
                        icon: "error",
                        confirmButtonText: "Okay"
                    });

                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });
                        firstInvalidField.focus();
                    }
                    return;
                }

                // Confirmation before submission
                Swal.fire({
                    title: "Are you sure?",
                    text: "Please confirm your internship application submission.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Submit",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();

                        setTimeout(() => {
                            window.location.href = "/thank-you";
                        }, 2000);
                    }
                });
            });
        });
    </script>
@endsection
