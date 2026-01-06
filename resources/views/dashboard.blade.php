@extends('layouts.app')

@section('title', 'HR Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mt-4">Welcome back, {{ Auth::user()->name }}</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs mb-0" id="dashboardTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="recruitment-tab" data-toggle="tab" href="#recruitment" role="tab">Recruitment Pipeline</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="employees-tab" data-toggle="tab" href="#employees" role="tab">Employees</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="interns-tab" data-toggle="tab" href="#interns" role="tab">Interns</a>
            </li>
        </ul>
        <div class="d-flex align-items-center">
            <label for="dateRange" class="mb-0 mr-2">Date Range:</label>
            <div class="d-inline-flex align-items-center">
                <select id="predefinedRange" class="form-control form-control-sm mr-2" style="width: auto;">
                    <option value="all">All Time</option>
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="90">Last 90 Days</option>
                </select>
            </div>
        </div>
    </div>


    <div class="tab-content mt-3" id="dashboardTabsContent">
        <!-- Recruitment Pipeline -->
        <div class="tab-pane fade show active" id="recruitment" role="tab">
            <div class="card p-4 shadow-sm">
                <h4 class="font-weight-bold">Recruitment Pipeline Overview</h4>
                <div class="row text-center d-flex justify-content-center">
                    <div class="col-md-4">
                        <h1 class="display-3 font-weight-bold text-primary">{{ $totalApplications}}</h1>
                        <p class="font-weight-bold">Applications Received</p>
                    </div>
                </div>
                <!--  -->
                <h5 class="mt-4 mb-3">Current Application Status</h5>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="For Screening">{{ $stages['For Screening'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">For Screening</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="Shortlisted">{{ $stages['Shortlisted'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Shortlisted</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="For Interview">{{ $stages['For Interview'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">For Interview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="Scheduled for Interview">{{ $stages['Scheduled for Interview'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Scheduled for Interview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="Completed Interview">{{ $stages['Completed Interview'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Completed Interview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="Offer Made">{{ $stages['Offer Made'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Offer Made</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="Hired">{{ $stages['Hired'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Hired</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" id="pipeline" data-status="Rejected">{{ $stages['Rejected'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <!-- Avarage Time Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0 text-primary font-weight-bold">Average Time to Fill Positions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <h3 class="text-success mb-2">{{ $interns['avg_days'] }}</h3>
                                        <small class="text-muted">{{ $interns['count'] }} applicants, {{ $interns['hired_count'] }} hired</small>
                                        <h4 class="text-muted mb-0">Interns</h4>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-info">
                                    <div class="card-body text-center">
                                        <h3 class="text-info mb-2">{{ $fullTime['avg_days'] }}</h3>
                                        <small class="text-muted">{{ $fullTime['count'] }} applicants, {{ $fullTime['hired_count'] }} hired</small>
                                        <h4 class="text-muted mb-0">Full-Time</h4>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-warning">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning mb-2">{{ $partTime['avg_days'] }}</h3>
                                        <small class="text-muted">{{ $partTime['count'] }} applicants, {{ $partTime['hired_count'] }} hired</small>
                                        <h4 class="text-muted mb-0">Part-Time</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>

        <!-- Employees -->
        <div class="tab-pane fade" id="employees" role="tab">
            <div class="card p-4 shadow-sm">
                <h5 class="font-weight-bold">Employee Applications Summary</h5>
                <div class="row text-center d-flex justify-content-center">
                    <div class="col-md-4">
                        <h1 class="display-3 font-weight-bold text-primary">{{ $fullTime['count']}}</h1>
                        <p class="font-weight-bold">Full-Time Applications Received</p>
                    </div>
                    <div class="col-md-4">
                        <h1 id="part" class="display-3 font-weight-bold text-primary">{{ $partTime['count']}}</h1>
                        <p class="font-weight-bold">Part-Time Applications Received</p>
                    </div>
                </div>

                <!-- Full-Time Applications -->
                <div class="card p-3 mt-4">
                    <h2>Full-Time Applications</h2>
                    <h5 class="mt-4">Applications by Position</h5>

                    <table class="table mt-4" id="full-time" data-status-table="true">
                        <thead>
                            <tr>
                                <th>Position Title</th>
                                <th>Application Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fullTimePositions as $position)
                            <tr>
                                <td>{{ $position->jobPosition->position_title }}</td>
                                <td>{{ $position->count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- full-Time Application Status -->
                    <h5 class="mt-4 mb-3">Current Application Status</h5>
                    <div class="row row-cols-2 row-cols-md-4 g-4" id="full-time-section">
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="For Screening">{{ $employeeApplicants['For Screening'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">For Screening</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Shortlisted">{{ $employeeApplicants['Shortlisted'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Shortlisted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="For Interview">{{ $employeeApplicants['For Interview'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">For Interview</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Scheduled for Interview">{{ $employeeApplicants['Scheduled for Interview'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Scheduled for Interview</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Completed Interview">{{ $employeeApplicants['Completed Interview'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Completed Interview</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Offer Made">{{ $employeeApplicants['Offer Made'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Offer Made</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Hired">{{ $employeeApplicants['Hired'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Hired</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Rejected">{{ $employeeApplicants['Rejected'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Rejected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                </div>


                <!-- Part-Time Applications -->
                <div class="card p-3 mt-4">
                    <div class="row text-center d-flex justify-content-center">
                    </div>
                    <h2>Part-Time Applications</h2>
                    <h5 class="mt-4">Applications by Position</h5>
                    <table class="table mt-4" id="part-time" data-status-table="true">
                        <thead>
                            <tr>
                                <th>Position Title</th>
                                <th>Application Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partTimePositions as $position)
                            <tr>
                                <td>{{ $position->jobPosition->position_title }}</td>
                                <td>{{ $position->count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Part-Time Application Status -->
                    <h5 class="mt-4 mb-3">Current Application Status</h5>
                    <div class="row row-cols-2 row-cols-md-4 g-4" id="part-time-section">
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="For Screening">{{ $partEmployeeApplicants['For Screening'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">For Screening</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Shortlisted">{{ $partEmployeeApplicants['Shortlisted'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Shortlisted</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="For Interview">{{ $partEmployeeApplicants['For Interview'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">For Interview</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Scheduled for Interview">{{ $partEmployeeApplicants['Scheduled for Interview'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Scheduled for Interview</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Completed Interview">{{ $partEmployeeApplicants['Completed Interview'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Completed Interview</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Offer Made">{{ $partEmployeeApplicants['Offer Made'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Offer Made</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Hired">{{ $partEmployeeApplicants['Hired'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Hired</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-primary hover-shadow">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <h3 class="text-primary mb-2 display-5" data-status="Rejected">{{ $partEmployeeApplicants['Rejected'] ?? '0' }}</h3>
                                    <p class="mb-0 text-muted small">Rejected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                </div>


            </div>
        </div>

        <!-- Interns -->
        <div class="tab-pane fade" id="interns" role="tab">
            <div class="card p-4 shadow-sm">
                <h5 class="font-weight-bold">Intern Applications Summary</h5>
                <div class="row text-center d-flex justify-content-center">
                    <div class="col-md-4">
                        <h1 class="display-3 font-weight-bold text-primary">{{ $interns['count'] }}</h1>
                        <p class="font-weight-bold">Total Intern Applications Received</p>
                    </div>
                </div>

                <h5 class="mt-4">Intern Applications by Position</h5>
                <table class="table mt-4" id="intern" data-status-table="true">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Application Count</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($internPositions as $position)
                        <tr>
                            <td>{{ $position->jobPosition->position_title }}</td>
                            <td>{{ $position->count < 0 ? 0 : $position->count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <h5 class="mt-4">Intern Applications by University</h5>
                <table class="table mt-4" id="education" data-status-table="true">
                    <thead>
                        <tr>
                            <th>University</th>
                            <th>Application Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($universities as $university)
                        <tr>
                            <td>{{ $university->university }}</td>
                            <td>{{ $university->count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <!-- Intern Application Status -->
                <h5 class="mt-4 mb-3">Current Intern Application Status</h5>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="For Screening">{{ $internApplicants['For Screening'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">For Screening</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="Shortlisted">{{ $internApplicants['Shortlisted'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Shortlisted</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="For Interview">{{ $internApplicants['For Interview'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">For Interview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="Scheduled for Interview">{{ $internApplicants['Scheduled for Interview'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Scheduled for Interview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="Completed Interview">{{ $internApplicants['Completed Interview'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Completed Interview</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="Offer Made">{{ $internApplicants['Offer Made'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Offer Made</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="Hired">{{ $internApplicants['Hired'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Hired</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm border-primary">
                            <div class="card-body text-center">
                                <h3 class="text-primary mb-2" data-status="Rejected">{{ $internApplicants['Rejected'] ?? '0' }}</h3>
                                <p class="mb-0 text-muted">Rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>

        @endsection