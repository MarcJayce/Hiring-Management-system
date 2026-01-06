@extends('layouts.app')

@section('title', 'Edit Position')

@section('content')
<form action="{{ route('job_positions.update', $job->id) }}" method="POST" id="jobForm">
    <div class="container mt-4 bg-white p-4">
        <h2 class="mb-4">Edit Job Position</h2>

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="position_title" class="form-label me-2" style="width: 150px;">Position Title:</label>
                <input type="text" class="form-control" id="position_title" name="position_title" value="{{ $job->position_title }}" required>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="department" class="form-label me-2" style="width: 150px;">Department:</label>
                <input type="text" class="form-control" id="department" name="department" value="{{ $job->department }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label class="form-label me-2" style="width: 115px;">Work Set-Up:</label>
                <select class="form-select flex-grow-1 p-2" name="work_setup" required>
                    <option value="Hybrid" {{ $job->work_setup == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    <option value="On-site" {{ $job->work_setup == 'On-site' ? 'selected' : '' }}>On-site</option>
                    <option value="Work From Home" {{ $job->work_setup == 'Work From Home' ? 'selected' : '' }}>Work From Home</option>
                </select>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="job_duration" class="form-label me-2" style="width: 150px;">Job Duration:</label>
                <input type="text" class="form-control" id="job_duration" name="job_duration" value="{{ $job->job_duration }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label class="form-label me-2" style="width: 115px;">Reports To:</label>
                <select class="form-select flex-grow-1 p-2" name="reports_to" required>
                    <option value="Marketing Manager" {{ $job->reports_to == 'Marketing Manager' ? 'selected' : '' }}>Marketing Manager</option>
                </select>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="work_hours" class="form-label me-2" style="width: 150px;">Work Hours:</label>
                <input type="text" class="form-control" id="work_hours" name="work_hours" value="{{ $job->work_hours }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="compensation" class="form-label me-2" style="width: 115px;">Compensation:</label>
                <select class="form-select flex-grow-1 p-2" name="compensation" required>
                    <option value="Allowance Provided" {{ $job->compensation == 'Allowance Provided' ? 'selected' : '' }}>Allowance Provided</option>
                    <option value="No Allowance Provided" {{ $job->compensation == 'No Allowance Provided' ? 'selected' : '' }}>No Allowance Provided</option>
                </select>
            </div>
            <div class="col-md-6 mb-3 d-flex align-items-center">
                <label for="application_type" class="form-label me-2" style="width: 115px;">Application Type:</label>
                <select class="form-select flex-grow-1 p-2" name="application_type" required>
                    <option value="Full-Time Employee" {{ $job->application_type == 'Full-Time Employee' ? 'selected' : '' }}>Full-Time Employee</option>
                    <option value="Part-Time Employee" {{ $job->application_type == 'Part-Time Employee' ? 'selected' : '' }}>Part-Time Employee</option>
                    <option value="Intern" {{ $job->application_type == 'Intern' ? 'selected' : '' }}>Intern</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="position_description" class="form-label">Position Description:</label>
            <textarea class="form-control" id="position_description" name="position_description" rows="3" required>{{ $job->position_description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="key_responsibilities" class="form-label">Key Responsibilities:</label>
            <div id="editor">{!! $job->key_responsibilities !!}</div>
            <input type="hidden" name="key_responsibilities" id="key_responsibilities">
        </div>

        <div class="mb-3">
            <label for="benefits" class="form-label">Benefits & Learning Opportunities:</label>
            <textarea class="form-control" id="benefits" name="benefits" rows="3">{{ $job->benefits }}</textarea>
        </div>
    </div>

    <div class="container mt-4 bg-white p-4">
        <div class="row">
            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="start_date" class="form-label me-2" style="width: 150px;">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $job->start_date }}" required>
            </div>

            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="end_date" class="form-label me-2" style="width: 150px;">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $job->end_date }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="availability" class="form-label me-2" style="width: 150px;">Availability:</label>
                <input type="number" class="form-control" id="availability" name="availability" value="{{ $job->availability }}" required>
            </div>

            <div class="col-md-6 d-flex align-items-center mb-3">
                <label for="status" class="form-label" style="width: 115px;">Status:</label>
                <select class="form-select flex-grow-1 p-2" name="status" required>
                    <option value="Active" {{ $job->status == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Closed" {{ $job->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center gap-2 mt-3">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
    </div>
</form>

<!-- QuillJS for Key Responsibilities -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    const quill = new Quill("#editor", {
        theme: "snow"
    });
    document.getElementById("jobForm").addEventListener("submit", function(event) {
        document.getElementById("key_responsibilities").value = quill.root.innerHTML;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('jobForm').addEventListener('submit', function(event) {
        event.preventDefault();
        Swal.fire({
                icon: 'success',
                title: 'Position updated successfully!',
                timer: 1500
            })
            .then(() => this.submit());
    });
</script>
@endsection