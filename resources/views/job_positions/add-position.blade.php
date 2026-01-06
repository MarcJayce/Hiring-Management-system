@extends('layouts.app')

@section('title', 'Add Position')

@section('content')
<form action="{{ route('job_positions.store') }}" method="POST" id="jobForm">
    <div class="container mt-4 bg-white p-4">
        <h2 class="mb-4">Job Position Form</h2>

        @csrf
        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="position_title" class="form-label me-2" style="width: 150px;">Position Title:</label>
                <input type="text" class="form-control" id="position_title" name="position_title" required>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="department" class="form-label me-2" style="width: 150px;">Department:</label>
                <input type="text" class="form-control" id="department" name="department" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label class="form-label me-2" style="width: 115px;">Work Set-Up:</label>
                <select class="form-select flex-grow-1 p-2" name="work_setup" required>
                    <option value="Hybrid">Hybrid</option>
                    <option value="On-site">On-site</option>
                    <option value="Work From Home">Work From Home</option>
                </select>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="job_duration" class="form-label me-2" style="width: 150px;">Job Duration:</label>
                <input type="text" class="form-control" id="job_duration" name="job_duration" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label class="form-label me-2" style="width: 115px;">Reports To:</label>
                <select class="form-select flex-grow-1 p-2" name="reports_to" required>
                    <option value="Marketing Manager">Marketing Manager</option>
                    <option value="Marketing Director">Marketing Director</option>
                    <option value="Digital Marketing Manager">Digital Marketing Manager</option>
                    <option value="SEO Specialist">SEO Specialist</option>
                    <option value="Content Marketing Manager">Content Marketing Manager</option>
                    <option value="Software Engineering Lead">Software Engineering Lead</option>
                    <option value="Front-End Development Lead">Front-End Development Lead</option>
                    <option value="Back-End Development Lead">Back-End Development Lead</option>
                    <option value="Data Science Lead">Data Science Lead</option>
                    <option value="Data Analytics Manager">Data Analytics Manager</option>
                    <option value="IT Operations Manager">IT Operations Manager</option>
                    <option value="Network Infrastructure Manager">Network Infrastructure Manager</option>
                    <option value="IT Security Manager">IT Security Manager</option>
                    <option value="IT Manager">IT Manager</option>
                </select>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="work_hours" class="form-label me-2" style="width: 150px;">Work Hours:</label>
                <input type="text" class="form-control" id="work_hours" name="work_hours" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="compensation" class="form-label me-2" style="width: 115px;">Compensation:</label>
                <select class="form-select flex-grow-1 p-2" name="compensation" required>
                    <option value="Allowance Provided">Allowance Provided</option>
                    <option value="No Allowance Provided">No Allowance Provided</option>
                </select>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="application_type" class="form-label me-2" style="width: 115px;">Application Type:</label>
                <select class="form-select flex-grow-1 p-2" name="application_type" required>
                    <option value="Full-Time Employee">Full-Time Employee</option>
                    <option value="Part-Time Employee">Part-Time Employee</option>
                    <option value="Intern">Intern</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="position_description" class="form-label">Position Description:</label>
            <textarea class="form-control" id="position_description" name="position_description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="key_responsibilities" class="form-label">Key Responsibilities:</label>
            <div id="key_responsibilities_editor"></div>
            <input type="hidden" name="key_responsibilities" id="key_responsibilities">
        </div>

        <div class="mb-3">
            <label for="benefits" class="form-label">Benefits & Learning Opportunities:</label>
            <div id="benefits_editor"></div>
            <input type="hidden" name="benefits" id="benefits">
        </div>
    </div>

    <div class="container mt-4 bg-white p-4">
        <div class="row">
            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="start_date" class="form-label me-2" style="width: 150px;">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>

            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="end_date" class="form-label me-2" style="width: 150px;">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="availability" class="form-label me-2" style="width: 150px;">Availability:</label>
                <input type="number" class="form-control" id="availability" name="availability" required>
            </div>

            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="status" class="form-label" style="width: 115px;">Status:</label>
                <select class="form-select flex-grow-1 p-2" name="status" required>
                    <option value="Active">Active</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center gap-2 mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-danger">Cancel</button>
    </div>
</form>

<!-- QuillJS Text Area -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    const keyResponsibilitiesQuill = new Quill("#key_responsibilities_editor", {
        theme: "snow",
    });
    
    const benefitsQuill = new Quill("#benefits_editor", {
        theme: "snow",
    });

    document.getElementById("jobForm").addEventListener("submit", function(event) {
        var keyResponsibilitiesInput = document.getElementById("key_responsibilities");
        var benefitsInput = document.getElementById("benefits");

        if (keyResponsibilitiesInput) {
            keyResponsibilitiesInput.value = keyResponsibilitiesQuill.root.innerHTML;
        }

        if (benefitsInput) {
            benefitsInput.value = benefitsQuill.root.innerHTML;
        }
    });

    keyResponsibilitiesQuill.on('text-change', function() {
        var editor = document.querySelector('.ql-editor');
        editor.style.height = 'auto';
        editor.style.height = (editor.scrollHeight) + 'px';
    });

    benefitsQuill.on('text-change', function() {
        var editor = document.querySelector('.ql-editor');
        editor.style.height = 'auto';
        editor.style.height = (editor.scrollHeight) + 'px';
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('jobForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default submission

        const form = this; // Store reference to form
        document.getElementById("key_responsibilities").value = keyResponsibilitiesQuill.root.innerHTML; // Ensure Quill data is set
        document.getElementById("benefits").value = benefitsQuill.root.innerHTML; // Ensure Quill data is set

        Swal.fire({
            icon: 'success',
            title: 'Position has been successfully added!',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            form.submit();
        });
    });
</script>
@endsection
