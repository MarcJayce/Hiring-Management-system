@extends('layouts.app')

@section('title', 'All Candidates')

@section('content')

    <div class="container-fluid">
        <!-- Header with Dropdown -->
        <div class="d-flex align-items-center mt-4 mb-3">
            <div class="dropdown">
                <h2 class="mb-0 dropdown-toggle" type="button" id="candidateTypeDropdown" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
                    {{ ucfirst(request()->get('type', 'all') == 'all' ? 'All Candidates' : request()->get('type')) }}
                </h2>
                <div class="dropdown-menu" aria-labelledby="candidateTypeDropdown">
                    <a class="dropdown-item" href="{{ request()->url() }}">All Candidates</a>
                    <a class="dropdown-item" href="{{ request()->url() }}?type=intern">Intern</a>
                    <a class="dropdown-item" href="{{ request()->url() }}?type=employee">Employee</a>
                </div>
            </div>
        </div>

        <!-- Screening Steps as Tabs -->
        <ul class="nav nav-tabs mb-3">
            @php
                $steps = [
                    'Screening' => 'primary',
                    'Shortlisted' => 'success',
                    'For Interview' => 'info',
                    'Interview Schedule' => 'warning',
                    'Completed Interview' => 'secondary',
                    'Offer' => 'warning',
                    'Hired' => 'dark',
                    'Rejected' => 'danger',
                ];
                $activeStep = request()->get('step', 'screening');
            @endphp
            @foreach ($steps as $step => $color)
                <li class="nav-item">
                    <a class="nav-link {{ strtolower(str_replace(' ', '_', $step)) == $activeStep ? 'active' : '' }}"
                        href="?step={{ strtolower(str_replace(' ', '_', $step)) }}&type={{ request()->get('type', 'all') }}">
                        {{ $step }}
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- Candidates Section -->
        <div class="bg-light p-3 rounded mb-4">
            @if ($candidates->isEmpty())
                <div class="alert alert-warning text-center" id="noCandidatesAlert">
                    No candidates found for this type!
                </div>
            @else
                <table id="candidateTable" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Applicant ID</th>
                            <th>Name</th>
                            <th>No. of Days</th>
                            <th>Position</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>

                            @if (request()->get('step') === 'for_interview')
                                <th>Email Status</th>
                                <th>Date Submitted</th>
                            @elseif(request()->get('step') === 'interview_schedule')
                                <th>Interviewer</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Interview Status</th>
                            @elseif(request()->get('step') === 'completed_interview')
                                <th>Email</th>
                                <th>Preferred Start Date</th>
                                <th>Interview Remarks</th>
                            @elseif(request()->get('step') === 'offer')
                                <th>Offer Date</th>
                                <th>Offer End Date</th>
                                <th>Offered Start Date</th>
                            @elseif(request()->get('step') === 'hired')
                                <th>Department</th>
                                <th>Hiring Date</th>
                                <th>Email Status</th>
                            @elseif(request()->get('step') === 'rejected')
                                <th>Email Status</th>
                            @else
                                <th>Date Submitted</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidates->sortBy('created_at') as $candidate)
                            <tr class="candidate-row" data-toggle="modal" data-target="#candidateModal{{ $candidate->id }}">
                                <td class="text-center font-weight-bold">{{ $candidate->id }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <strong>{{ $candidate->first_name }} {{ $candidate->last_name }}</strong>
                                        <small class="text-muted">{{ $candidate->email }}</small>
                                    </div>
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ (int) \Carbon\Carbon::parse($candidate->created_at)->diffInDays(\Carbon\Carbon::now()) }}
                                </td>
                                <td class="text-center font-weight-bold">{{ $candidate->position }}</td>
                                <td class="text-center font-weight-bold">{{ $candidate->type }}</td>
                                <td class="text-center font-weight-bold">{{ $candidate->city }}</td>
                                <td class="text-center font-weight-bold">{{ $candidate->status ?? 'Pending' }}</td>

                                @if (request()->get('step') === 'for_interview')
                                    <!-- <td class="text-center font-weight-bold">Online</td> <td class="text-center font-weight-bold">{{ $candidate->requested_by ?? 'Not set' }}</td> -->
                                    <td class="text-center font-weight-bold">{{ $candidate->email_status ?? 'Not Sent' }}
                                    <td class="text-center font-weight-bold">{{ $candidate->created_at->format('M d, Y') }}
                                    </td>
                                @elseif(request()->get('step') === 'interview_schedule')
                                    @php
                                        $latestSchedule = $candidate->interviewSchedules
                                            ->sortByDesc('created_at')
                                            ->first();
                                    @endphp

                                    <td class="text-center font-weight-bold">
                                        {{ $latestSchedule->interviewer->name ?? 'Not set' }}
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        {{ $latestSchedule ? $latestSchedule->date . ' ' . $latestSchedule->time : 'Not set' }}
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        {{ $latestSchedule->location ?? 'Not set' }}
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        {{ $latestSchedule->status ?? 'Not set' }}
                                    </td>
                                @elseif(request()->get('step') === 'completed_interview')
                                    <!-- Email -->
                                    <td class="text-center font-weight-bold">{{ $candidate->email }}</td>
                                    <!-- Start date -->
                                    <td class="text-center font-weight-bold">
                                        {{ strtolower($candidate->type) === 'intern' ? $candidate->internship->desired_start_date : $candidate->jobSpecifics->available_date }}
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        @foreach ($candidate->interviewSchedules as $schedule)
                                            {{ $loop->iteration }} - {{ $schedule->status }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                @elseif(request()->get('step') === 'offer')
                                    <td class="text-center font-weight-bold">{{ $candidate->offer_date ?? 'Not set' }}</td>
                                    <td class="text-center font-weight-bold">{{ $candidate->offer_end_date ?? 'Not set' }}
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $candidate->start_date ?? 'Not set' }}</td>
                                @elseif(request()->get('step') === 'hired')
                                    <td class="text-center font-weight-bold">{{ $candidate->department ?? 'Not set' }}</td>
                                    <td class="text-center font-weight-bold">
                                        {{ $candidate->hiring_date ? \Carbon\Carbon::parse($candidate->hiring_date)->format('Y-m-d') : 'Not set' }}
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $candidate->email_status ?? 'Not Sent' }}
                                    </td>
                                @elseif(request()->get('step') === 'rejected')
                                    <td class="text-center font-weight-bold">{{ $candidate->email_status ?? 'Not Sent' }}
                                    </td>
                                @else
                                    <td class="text-center font-weight-bold">{{ $candidate->created_at->format('M d, Y') }}
                                    </td>
                                @endif
                            </tr>

                            <!-- Modal for each candidate -->
                            <div class="modal fade" id="candidateModal{{ $candidate->id }}" tabindex="-1"
                                aria-labelledby="candidateModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-auto">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="candidateModalLabel">Actions for
                                                {{ $candidate->first_name }} {{ $candidate->last_name }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body d-flex justify-content-center gap-3">
                                            <a href="{{ route('candidates.view', ['id' => $candidate->id]) }}"
                                                class="btn btn-primary btn-sm d-flex align-items-center justify-content-center btn-hover"
                                                style="height: 50px; min-width:110px; margin-right: 8px; margin-top: 5px;">
                                                VIEW CV
                                            </a>

                                            @if (request()->get('step') === 'for_interview')
                                                <!-- Schedule Interview Button -->
                                                <button type="button"
                                                    class="btn btn-warning btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:160px;"
                                                    onclick="openInterviewInviteModal({{ $candidate->id }}, '{{ addslashes($candidate->name) }}')">
                                                    SEND INTERVIEW INVITE
                                                </button>

                                                <button
                                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px; margin-right: 8px;"
                                                    onclick="window.location.href='{{ route('interviews.schedule.withApplicant', ['id' => $candidate->id]) }}'">
                                                    SCHEDULE INTERVIEW
                                                </button>
                                            @elseif(request()->get('step') === 'interview_schedule')
                                                <!-- Complete Button -->
                                                <button
                                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px; margin-right: 8px;"
                                                    data-interview-id="{{ $latestSchedule->id }}"
                                                    onclick="conductInterview(this)">
                                                    CONDUCT INTERVIEW
                                                </button>
                                            @elseif(request()->get('step') === 'completed_interview')
                                                <!-- Offer Button -->
                                                <button
                                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px; margin-right: 8px;"
                                                    onclick="openOfferDialog({{ $candidate->id }},'{{ addslashes($candidate->jobPosition->position_title) }}','{{ addslashes($candidate->first_name) }}','{{ addslashes($candidate->last_name) }}')">
                                                    OFFER
                                                </button>

                                                <!-- Schedule Interview Button -->
                                                <button
                                                    class="btn btn-warning btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px; margin-right: 8px;"
                                                    onclick="window.location.href='{{ route('interviews.schedule.withApplicant', ['id' => $candidate->id]) }}'">
                                                    NEW INTERVIEW
                                                </button>

                                                <!-- View Interview Button -->
                                                <button
                                                    class="btn btn-info btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px; margin-right: 8px;"
                                                    onclick="toggleInterviewRecords({{ $candidate->id }}); return false;">
                                                    VIEW INTERVIEWS
                                                </button>
                                            @elseif(request()->get('step') === 'offer')
                                                @if ($candidate->status === 'Offer Accepted')
                                                    <!-- Hire Button -->
                                                    <button
                                                        class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="openHireDialog({{ $candidate->id }})">
                                                        HIRE
                                                    </button>
                                                @endif
                                                @if ($candidate->status !== 'Offer Accepted')
                                                    <button
                                                        class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="markOfferAccepted({{ $candidate->id }}); return false;">
                                                        OFFER ACCEPTED
                                                    </button>
                                                @endif
                                            @elseif(request()->get('step') === 'hired')
                                                <!-- No Button Displayed -->
                                                @if ($candidate->email_status !== 'Hiring Email Sent')
                                                    <button
                                                        class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="sendHiredEmail({{ $candidate->id }}, '{{ $candidate->email }}')">
                                                        SEND HIRE EMAIL
                                                    </button>
                                                @endif

                                                <!-- When the step is 'hired', no button is shown -->
                                            @elseif(request()->get('step') === 'rejected')
                                                <!-- Reconsider Button -->
                                                <button
                                                    class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px; margin-right: 8px;"
                                                    onclick="reconsiderCandidate({{ $candidate->id }})">
                                                    RECONSIDER
                                                </button>
                                                @if ($candidate->status !== 'Rejected by Applicant')
                                                    <form method="POST"
                                                        action="{{ route('candidates.rejectByApplicant', $candidate->id) }}"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn btn-warning btn-sm d-flex align-items-center justify-content-center action-button"
                                                            style="height: 50px; min-width: 160px; margin-right: 8px;">
                                                            REJECTED BY APPLICANT
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($candidate->email_status !== 'Rejection Email Sent')
                                                    <button
                                                        class="btn btn-danger btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="sendRejectionEmail({{ $candidate->id }}, '{{ $candidate->email }}')">
                                                        SEND REJECTION EMAIL
                                                    </button>
                                                @endif
                                            @else
                                                <!-- Shortlist or For Interview Buttons -->
                                                @if (request()->get('step') === 'shortlisted')
                                                    <button
                                                        class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="moveToForInterview({{ $candidate->id }})">
                                                        FOR INTERVIEW
                                                    </button>
                                                @else
                                                    <button
                                                        class="btn btn-success btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="shortlistCandidate({{ $candidate->id }})">
                                                        SHORTLIST
                                                    </button>
                                                    <button
                                                        class="btn btn-info btn-sm d-flex align-items-center justify-content-center action-button"
                                                        style="height: 50px; min-width:110px; margin-right: 8px;"
                                                        onclick="moveToForInterview({{ $candidate->id }})">
                                                        FOR INTERVIEW
                                                    </button>
                                                @endif
                                            @endif

                                            @if (request()->get('step') !== 'rejected')
                                                <button
                                                    class="btn btn-danger btn-sm d-flex align-items-center justify-content-center action-button"
                                                    style="height: 50px; min-width:110px;"
                                                    onclick="rejectCandidate({{ $candidate->id }})">REJECT
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div>
        @foreach ($candidates as $candidate)
            <table id="interviewRecords-{{ $candidate->id }}" class="table table-bordered mt-3" style="display: none;">
                <thead>
                    <tr>
                        <th colspan="7">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Complete Interview Records of {{ $candidate->first_name }}
                                    {{ $candidate->last_name }}</span>
                                <button class="btn btn-sm btn-danger"
                                    onclick="closeInterviewTable({{ $candidate->id }}); return false;">
                                    Close
                                </button>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Interview Round</th>
                        <th>Interviewer</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($candidate->interviewSchedules as $interview)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $interview->round }}</td>
                            <td>{{ $interview->interviewer->name ?? 'Not set' }}</td>
                            <td>{{ $interview->date ? \Carbon\Carbon::parse($interview->date)->format('M d, Y') : 'Not set' }}
                            </td>
                            <td>{{ $interview->time ? \Carbon\Carbon::parse($interview->time)->format('h:i A') : 'Not set' }}
                            </td>
                            <td>{{ $interview->status ?? 'Not set' }}</td>
                            <td>
                                <!-- Action buttons for View/Details -->
                                <a href="{{ route('interview.view', $interview->id) }}"
                                    class="btn btn-info btn-sm">View</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>

    <script>
        let currentlyOpenTable = null;

        function toggleInterviewRecords(candidateId) {
            const table = document.getElementById(`interviewRecords-${candidateId}`);

            // Close previously opened table if different from current one
            if (currentlyOpenTable && currentlyOpenTable !== table) {
                currentlyOpenTable.style.display = 'none';
            }

            // Toggle current table
            table.style.display = table.style.display === 'none' ? 'table' : 'none';

            // Update currently open table reference
            if (table.style.display === 'table') {
                currentlyOpenTable = table;
            } else {
                currentlyOpenTable = null;
            }
        }

        function closeInterviewTable(candidateId) {
            const table = document.getElementById(`interviewRecords-${candidateId}`);
            table.style.display = 'none';
            currentlyOpenTable = null;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let table = new DataTable('#candidateTable');
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: `{!! session('success') !!}`,
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    @endif

    <script src="{{ asset('js/candidates.js') }}"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>



@endsection
