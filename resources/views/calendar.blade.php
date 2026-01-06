@extends('layouts.app')

@section('title', 'Interview Calendar')

@section('content')
<div class="container mt-4">
    <div class="card border-0">
        <div class="card-header bg-white border-0">
            <h2 class="mb-0">Interview Schedule Calendar</h2>
        </div>
        <div class="card-body p-0">
            <div id="calendar" class="p-3"></div>
        </div>
    </div>
</div>

<!-- Interview Details Modal -->
<div class="modal fade" id="interviewModal" tabindex="-1" role="dialog" aria-labelledby="interviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-white border-0">
                <h5 class="modal-title" id="interviewModalLabel">Interview Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Interview details will be loaded here -->
            </div>
            <div class="modal-footer border-0">
                <a href="#" class="btn" id="conductInterviewBtn" style="background-color: #e3f2fd;">
                    Conduct Interview
                </a>
                <button type="button" class="btn" style="background-color: #e3f2fd;" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar Assets -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@5.11.5/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@5.11.5/main.min.js"></script>

<style>
    /* Calendar event styling */
    .fc-event:hover {
        opacity: 0.8 !important;
        cursor: pointer;
    }

    .fc-daygrid-day:hover {
        background-color: #f8f9fa !important;
        cursor: pointer;
    }

    /* FullCalendar buttons */
    .fc .fc-button-primary {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        color: #212529;
    }

    .fc .fc-button-primary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #212529;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #212529;
    }

    /* Selected view button (month/week/day) */
    .fc .fc-button-active {
        background-color: #e9ecef !important;
        border-color: #dee2e6 !important;
        color: #212529 !important;
    }

    /* Header title styling */
    .fc-toolbar-title {
        color: #212529;
        font-weight: normal;
    }

    /* Today's date styling */
    .fc .fc-daygrid-day.fc-day-today {
        background-color: #f8f9fa;
    }

    /* Modal detail section */
    .modal-body {
        padding: 1.5rem;
        background-color: #ffffff;
    }

    .detail-item {
        margin-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 0.5rem;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .detail-label {
        font-weight: normal;
        color: #212529;
    }

    .detail-value {
        color: #212529;
    }

    /* Card styling */
    .card {
        border: none;
        background-color: #ffffff;
    }

    .card-header {
        padding: 1rem 1.5rem;
        background-color: #ffffff;
        border-bottom: none;
    }

    /* Modal footer button (close) */
    .modal-footer .btn-secondary {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        color: #212529;
    }

    .modal-footer .btn-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #212529;
    }

    /* Default FullCalendar button style */
    .fc .fc-button {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        color: #212529;
        padding: 0.5rem 1rem;
        margin: 0 2px;
        border-radius: 4px;
        font-weight: normal;
        transition: all 0.2s ease-in-out;
    }

    /* Hover effect */
    .fc .fc-button:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #212529;
    }

    /* Active/Selected buttons */
    .fc .fc-button-active {
        background-color: #e9ecef !important;
        color: #212529 !important;
        border-color: #dee2e6 !important;
    }

    /* Disabled state */
    .fc .fc-button:disabled {
        background-color: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            displayEventTime: false,
            events: '{{ route("calendar.fetchEvents") }}',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            },
            dayMaxEvents: true,
            eventClick: function(info) {
                var event = info.event;
                window.currentEventId = event.id; // Add this line

                var details = `
                    <div class="detail-item">
                        <div class="detail-label">Applicant</div>
                        <div class="detail-value">${event.extendedProps.applicant_name}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Interviewer</div>
                        <div class="detail-value">${event.extendedProps.interviewer_name}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Interview Type</div>
                        <div class="detail-value">${event.extendedProps.type}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">${event.start.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">${event.start.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Duration</div>
                        <div class="detail-value">${event.extendedProps.duration}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">${event.extendedProps.location}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Round</div>
                        <div class="detail-value">${event.extendedProps.round}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">${event.extendedProps.status}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Created</div>
                        <div class="detail-value">${new Date(event.extendedProps.created_at).toLocaleString()}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value">${new Date(event.extendedProps.updated_at).toLocaleString()}</div>
                    </div>
                `;

                document.getElementById('modalBody').innerHTML = details;
                $('#interviewModal').modal('show');
            }
        });

        // Add event listener for conduct interview button
        document.getElementById('conductInterviewBtn').addEventListener('click', function(e) {
            e.preventDefault();
            var event = calendar.getEventById(currentEventId);
            if (event) {
                window.location.href = `/interviews/conduct/${event.id}`;
            }
        });

        calendar.render();
    });
</script>
@endsection