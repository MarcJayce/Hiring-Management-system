@php
    $layout = Auth::check() ? 'layouts.app' : 'layouts.guest';
@endphp

@extends($layout)

@section('title', 'Job Application')

@section('content')
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <h1>Applying for {{ $job ? $job->position_title : '[Position Placeholder]' }}</h1>
        <div class="card p-4 shadow-lg" style="width: 100%; max-width: 900px;">
            <h2 class="text-center mb-4">Application Form</h2>
            <form id="internApplicationForm" action="{{ route('employee.store') }}" method="POST"
                enctype="multipart/form-data">
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

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mt-4 mb-3 font-weight-bold">Professional Experience</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addExperience()">+ Add
                        Experience</button>
                </div>

                <div id="experience-container">
                    <div class="experience-entry">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Company Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="professional_experience[0][company_name]"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>Job Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="professional_experience[0][job_title]"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>Start Date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="professional_experience[0][start_date]"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label>End Date (if applicable)</label>
                                <input type="date" class="form-control" name="professional_experience[0][end_date]">
                            </div>
                            <div class="col-md-12">
                                <label>Responsibilities<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="professional_experience[0][responsibilities]" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mt-4 mb-3 font-weight-bold">Education</h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addEducation()">+ Add Education</button>
                </div>

                <div id="education-container">
                    <div class="education-entry">
                        <div class="row">
                            <div class="col-md-6">
                                <label>University<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="education[0][university_name]" required>
                            </div>
                            <div class="col-md-6">
                                <label>Degree<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="education[0][degree_earned]" required>
                            </div>
                            <div class="col-md-6">
                                <label>Graduation Date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="education[0][graduation_date]" required>
                            </div>
                        </div>
                    </div>
                </div>
                <label>Certifications/Licenses<span class="text-danger">*</span></label>
                <textarea class="form-control" name="certifications" required></textarea>

                <h5 class="mt-4 mb-3 font-weight-bold">Skills and Abilities</h5>

                <div class="form-group">
                    <label>Relevant Technical Skills (Select all that apply)<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-6">
                            <label><input type="checkbox" name="technical_skills[]" value="Content Creation"> Content
                                Creation</label><br>
                            <label><input type="checkbox" name="technical_skills[]" value="SEO & SEM"> SEO &
                                SEM</label><br>
                            <label><input type="checkbox" name="technical_skills[]"
                                    value="Marketing Research and Analysis"> Marketing Research and Analysis</label><br>
                            <label><input type="checkbox" name="technical_skills[]" value="Brand Management"> Brand
                                Management</label><br>
                        </div>
                        <div class="col-md-6">
                            <label><input type="checkbox" name="technical_skills[]" value="Web Development"> Web
                                Development</label><br>
                            <label><input type="checkbox" name="technical_skills[]" value="Database Management"> Database
                                Management</label><br>
                            <label><input type="checkbox" name="technical_skills[]" value="Software Development">
                                Software Development</label><br>
                            <label><input type="checkbox" name="technical_skills[]" value="UI/UX Design"> UI/UX
                                Design</label><br>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Industry-Specific Knowledge<span class="text-danger">*</span></label>
                    <textarea name="industry_knowledge" class="form-control" rows="3"
                        placeholder="Describe industry knowledge..."></textarea>
                </div>

                <div class="form-group">
                    <label>Soft Skills (Select all that apply)<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-6">
                            <label><input type="checkbox" name="soft_skills[]" value="Communication">
                                Communication</label><br>
                            <label><input type="checkbox" name="soft_skills[]" value="Leadership"> Leadership</label><br>
                            <label><input type="checkbox" name="soft_skills[]" value="Problem-Solving">
                                Problem-Solving</label><br>
                            <label><input type="checkbox" name="soft_skills[]" value="Teamwork"> Teamwork</label><br>
                        </div>
                        <div class="col-md-6">
                            <label><input type="checkbox" name="soft_skills[]" value="Time Management"> Time
                                Management</label><br>
                            <label><input type="checkbox" name="soft_skills[]" value="Adaptability">
                                Adaptability</label><br>
                            <label><input type="checkbox" name="soft_skills[]" value="Creativity"> Creativity</label><br>
                            <label><input type="checkbox" name="soft_skills[]" value="Attention to Detail"> Attention to
                                Detail</label><br>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4 mb-3 font-weight-bold">Job Specifics</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Desired Salary (PHP)</label>
                            <input type="number" name="desired_salary" class="form-control"
                                placeholder="Enter desired salary">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Available Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="available_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Why are you interested in this Position?<span class="text-danger">*</span></label>
                    <input type="text" name="job_interest" class="form-control" required>
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
                    <label>References<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="referral_source" required>
                </div>

                <div class="form-group">
                    <label>Why should we hire you?<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="why_hire" rows="3" required></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let submitBtn = document.getElementById('submitBtn');
            if (!submitBtn) return;

            submitBtn.addEventListener('click', function(event) {
                event.preventDefault();

                let form = document.getElementById('internApplicationForm');
                let errors = [];
                let firstInvalidField = null;

                function checkField(field, message) {
                    if (field && !field.value.trim()) {
                        errors.push(message);
                        field.classList.add('is-invalid');
                        if (!firstInvalidField) firstInvalidField = field;
                    } else if (field) {
                        field.classList.remove('is-invalid');
                    }
                }

                function checkCheckboxGroup(name, message) {
                    let checkboxes = document.querySelectorAll(`input[name="${name}[]"]:checked`);
                    if (checkboxes.length === 0) {
                        errors.push(message);
                    }
                }

                function validateDate(startField, endField) {
                    if (startField && endField && startField.value && endField.value) {
                        let startDate = new Date(startField.value);
                        let endDate = new Date(endField.value);

                        if (endDate < startDate) {
                            errors.push("End Date cannot be earlier than Start Date.");
                            endField.classList.add("is-invalid");
                            if (!firstInvalidField) firstInvalidField = endField;
                        } else {
                            endField.classList.remove("is-invalid");
                        }
                    }
                }

                function isValidEmail(email) {
                    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                }

                function isValidPhone(phone) {
                    let phoneRegex = /^[0-9]{10,}$/; // Only numbers, at least 10 digits
                    return phoneRegex.test(phone);
                }

                // Validate Personal Information
                checkField(document.querySelector('input[name="first_name"]'), 'First Name is required.');
                checkField(document.querySelector('input[name="last_name"]'), 'Last Name is required.');
                checkField(document.querySelector('input[name="address"]'), 'Address is required.');
                checkField(document.querySelector('input[name="city"]'), 'City is required.');


                // Validate Email Format
                let emailInput = document.querySelector('input[name="email"]');
                if (emailInput && !isValidEmail(emailInput.value.trim())) {
                    errors.push('Please enter a valid email address.');
                    emailInput.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = emailInput;
                } else if (emailInput) {
                    emailInput.classList.remove('is-invalid');
                }

                let phoneInput = document.querySelector('input[name="phone"]');
                if (phoneInput && !isValidPhone(phoneInput.value.trim())) {
                    errors.push('Phone Number must be at least 10 digits.');
                    phoneInput.classList.add('is-invalid');
                    if (!firstInvalidField) firstInvalidField = phoneInput;
                } else if (phoneInput) {
                    phoneInput.classList.remove('is-invalid');
                }


                // Validate each professional experience entry
                document.querySelectorAll('.experience-entry').forEach((entry, index) => {
                    checkField(entry.querySelector(
                            `[name="professional_experience[${index}][company_name]"]`),
                        'Company Name is required.');
                    checkField(entry.querySelector(
                            `[name="professional_experience[${index}][job_title]"]`),
                        'Job Title is required.');
                    checkField(entry.querySelector(
                            `[name="professional_experience[${index}][start_date]"]`),
                        'Start Date is required.');
                    checkField(entry.querySelector(
                            `[name="professional_experience[${index}][responsibilities]"]`),
                        'Responsibilities are required.');

                    let startDateField = entry.querySelector(
                        `[name="professional_experience[${index}][start_date]"]`);
                    let endDateField = entry.querySelector(
                        `[name="professional_experience[${index}][end_date]"]`);
                    if (endDateField && endDateField.value) {
                        validateDate(startDateField, endDateField);
                    }
                });

                // Validate Education Information
                document.querySelectorAll('#education-container .education-entry').forEach((entry,
                    index) => {
                    let university = entry.querySelector(
                        `input[name="education[${index}][university_name]"]`);
                    let degree = entry.querySelector(
                        `input[name="education[${index}][degree_earned]"]`);
                    let graduationDate = entry.querySelector(
                        `input[name="education[${index}][graduation_date]"]`);

                    checkField(university,
                        `University Name is required for education entry #${index + 1}.`);
                    checkField(degree,
                        `Degree Earned is required for education entry #${index + 1}.`);
                    checkField(graduationDate,
                        `Graduation Date is required for education entry #${index + 1}.`);
                });

                // Validate Skills and Abilities
                checkCheckboxGroup("technical_skills", "Please select at least one Technical Skill.");
                checkCheckboxGroup("soft_skills", "Please select at least one Soft Skill.");
                checkField(document.querySelector('textarea[name="industry_knowledge"]'),
                    "Industry-Specific Knowledge is required.");

                // Validate Job Specifics
                checkField(document.querySelector('input[name="available_date"]'),
                    "Available Start Date is required.");
                checkField(document.querySelector('input[name="job_interest"]'),
                    "Please describe why you're interested in this position.");

                // Validate Other Information
                checkField(document.querySelector('input[name="resume"]'), "Please upload your Resume/CV.");
                checkField(document.querySelector('input[name="referral_source"]'),
                    "Please provide at least one reference.");
                checkField(document.querySelector('textarea[name="why_hire"]'),
                    "Please explain why we should hire you.");


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

    <!-- Multiple Education Entries Script -->
    <script>
        function addEducation() {
            let container = document.getElementById('education-container');
            let count = container.children.length;
            let html = `
        <hr> <!-- Divider for multiple entries -->
        <div class="education-entry">
            <div class="row">
                <div class="col-md-12">
                    <label>University<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="education[${count}][university_name]" required>
                </div>
                <div class="col-md-12">
                    <label>Degree<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="education[${count}][degree_earned]" required>
                </div>
                <div class="col-md-6">
                    <label>Graduation Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="education[${count}][graduation_date]" required>
                </div>
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-danger btn-sm remove-education" onclick="removeEntry(this)">Remove</button>
            </div>
        </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeEntry(button) {
            let entry = button.closest('.experience-entry') || button.closest('.education-entry');

            if (!entry) return;

            let prevElement = entry.previousElementSibling;

            // Remove the divider if it exists before the removed entry
            if (prevElement && prevElement.tagName === "HR") {
                prevElement.remove();
            }

            entry.remove();
        }
    </script>


    <script>
        function addExperience() {
            let container = document.getElementById('experience-container');
            let count = container.children.length;
            let html = `
        <hr> <!-- Divider for multiple entries -->
        <div class="experience-entry">
            <div class="row">
                <div class="col-md-6">
                    <label>Company Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="professional_experience[${count}][company_name]" required>
                </div>
                <div class="col-md-6">
                    <label>Job Title<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="professional_experience[${count}][job_title]" required>
                </div>
                <div class="col-md-6">
                    <label>Start Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="professional_experience[${count}][start_date]" required>
                </div>
                <div class="col-md-6">
                    <label>End Date (if applicable)</label>
                    <input type="date" class="form-control" name="professional_experience[${count}][end_date]">
                </div>
                <div class="col-md-12">
                    <label>Responsibilities<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="professional_experience[${count}][responsibilities]" required></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-danger btn-sm remove-experience" onclick="removeEntry(this)">Remove</button>
            </div>
        </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>

@endsection
