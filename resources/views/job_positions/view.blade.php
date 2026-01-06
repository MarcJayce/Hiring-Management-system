@extends('layouts.guest')

@section('content')
<div class="container mt-4 d-flex align-items-start">
    <!-- Left: Job Cards List (Fixed Height) -->
    <div class="container-fluid mt-4 d-flex" style="height: 90vh; overflow: hidden;">

        <div class="card w-50 p-3 shadow overflow-auto" style="max-height: 100%;">
            <h3>Available Positions</h3>
            @foreach ($jobPositions as $job)
            <div class="card mb-3 p-3 border">
                <h5>{{ $job->position_title }}</h5>
                <p><strong>Department:</strong> {{ $job->department }}</p>
                <p><strong>Work Setup:</strong> {{ $job->work_setup }}</p>
                <p><strong>Description:</strong> {{ Str::limit($job->position_description, 200) }}</p>

                <button class="btn btn-purple read-more-btn" data-id="{{ $job->id }}"
                    data-title="{{ $job->position_title }}" data-description="{{ $job->position_description }}"
                    data-department="{{ $job->department }}" data-worksetup="{{ $job->work_setup }}"
                    data-duration="{{ $job->job_duration }}" data-reports="{{ $job->reports_to }}"
                    data-hours="{{ $job->work_hours }}"
                    data-responsibilities="{{ base64_encode($job->key_responsibilities) }}"
                    data-benefits="{{ base64_encode($job->benefits) }}"
                    data-applicationtype="{{ $job->application_type }}">
                    Read More
                </button>

            </div>
            @endforeach
        </div>

        <!-- Right: Job Details Panel (Hidden Initially, but Space Reserved) -->
        <div class="card w-50 p-3 shadow" id="job-details"
            style="position: sticky; top: 20px; max-height: 80vh; overflow-y: auto; visibility: hidden;">

            <h3 id="job-title"><i class="fas fa-briefcase"></i> Select a Job</h3>
            <p><i class="fas fa-building"></i> <strong>Department:</strong> <span id="job-department"></span></p>
            <p><i class="fas fa-laptop-house"></i> <strong>Work Setup:</strong> <span id="job-worksetup"></span></p>
            <p><i class="fas fa-calendar-alt"></i> <strong>Duration:</strong> <span id="job-duration"></span></p>
            <p><i class="fas fa-user-tie"></i> <strong>Reports To:</strong> <span id="job-reports"></span></p>
            <p><i class="fas fa-clock"></i> <strong>Work Hours:</strong> <span id="job-hours"></span></p>
            <p><i class="fas fa-align-left"></i> <strong>Description:</strong></p>
            <p id="job-description"></p>
            <p><i class="fas fa-tasks"></i> <strong>Key Responsibilities:</strong></p>
            <div id="job-responsibilities" class="ql-editor"></div>
            <p><i class="fas fa-gift"></i> <strong>Benefits:</strong></p>
            <p id="job-benefits"></p>
            <a href="#" id="apply-now" class="btn btn-success"><i class="fas fa-paper-plane"></i> Apply Now</a>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.read-more-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('job-title').innerText = this.dataset.title;
            document.getElementById('job-description').innerText = this.dataset.description;
            document.getElementById('job-department').innerText = this.dataset.department;
            document.getElementById('job-worksetup').innerText = this.dataset.worksetup;
            document.getElementById('job-duration').innerText = this.dataset.duration;
            document.getElementById('job-reports').innerText = this.dataset.reports;
            document.getElementById('job-hours').innerText = this.dataset.hours;
            document.getElementById('job-benefits').innerHTML = atob(this.dataset.benefits);
            document.getElementById('job-responsibilities').innerHTML = atob(this.dataset
                .responsibilities);

            // Determine the correct application form URL
            let applicationType = this.dataset.applicationtype;
            let applyUrl = applicationType === 'Intern' ? `/apply/intern/` : `/apply/employee/`;

            // Update Apply Now button
            document.getElementById('apply-now').href = applyUrl + this.dataset.id;

            let detailsPanel = document.getElementById('job-details');
            detailsPanel.style.visibility = "visible";
            detailsPanel.style.opacity = "1";
            detailsPanel.style.transform = "translateY(0)";
        });
    });
</script>
@endsection