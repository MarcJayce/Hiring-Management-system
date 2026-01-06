@extends('layouts.app')

@section('title', 'Job Position Details')

@section('content')
<div class="container mt-5">
    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3">
                <h1>{{ $jobPosition->position_title }}</h1>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Department:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->department }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Work Set-Up:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->work_setup }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Job Duration:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->job_duration }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Reports To:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->reports_to }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Work Hours:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->work_hours }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Compensation:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->compensation }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Application Type:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->application_type }}</p>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label mb-0"><strong>Position Description:</strong></label>
                <p class="m-0">{{ $jobPosition->position_description }}</p>
            </div>
        </div>
    </div>

    <!-- Key Responsibilities & Benefits -->
    <div class="card mb-3">
        <div class="card-header">
            <strong>Key Responsibilities & Benefits</strong>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label"><strong>Key Responsibilities:</strong></label>
                <p class="m-0">{!! $jobPosition->key_responsibilities !!}</p>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Benefits & Learning Opportunities:</strong></label>
                <p class="m-0">{!! $jobPosition->benefits !!}</p>
            </div>
        </div>
    </div>

    <!-- Start and Status -->
    <div class="card mb-3">
        <div class="card-header">
            <strong>Start Date & Status</strong>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Start Date:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ \Carbon\Carbon::parse($jobPosition->start_date)->format('F j, Y') }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>End Date:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ \Carbon\Carbon::parse($jobPosition->end_date)->format('F j, Y') }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Availability:</strong></label>
                </div>
                <div class="col-md-9">
                    <p class="m-0">{{ $jobPosition->availability }}</p>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="form-label mb-0"><strong>Status:</strong></label>
                </div>
                <div class="col-md-9">
                    <span class="badge {{ $jobPosition->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $jobPosition->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>


    <a href="{{ url()->previous() }}" class="btn btn-primary">Back to List</a>
</div>
@endsection