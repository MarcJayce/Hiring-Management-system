@extends('layouts.guest')

@section('content')
    <div class="container-fluid px-4">
        @if (session('success'))
            <div class="alert alert-success" style="background-color: #763B88; color: white; border-color: #763B88;"
                id="successAlert">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('successAlert').style.display = 'none';
                }, 2000);
            </script>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header" style="background-color: #763B88; color: white;">Feedback Form</div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('feedback.store') }}" id="feedbackForm">
                            @csrf
                            <input type="hidden" name="interview_id" value="{{ $interview_id }}">
                            <div class="form-group row mb-4">
                                <label for="interview_date" class="col-md-3 col-form-label">Feedback Date</label>
                                <div class="col-md-9">
                                    <input id="feedback_date" type="date" class="form-control form-control-lg"
                                        name="feedback_date" required>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="interview_date" class="col-md-3 col-form-label">Interview Date and Time</label>
                                <div class="col-md-9">
                                    <input id="interview_date" type="text" class="form-control form-control-lg"
                                        name="interview_date"
                                        value="{{ $interviewSchedule->date }}, {{ $interviewSchedule->time }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="feedback" class="col-md-3 col-form-label">Interviewer</label>
                                <div class="col-md-9">
                                    <input id="interviewer_name" type="text" class="form-control form-control-lg"
                                        name="interviewer_name" value="{{ $interviewSchedule->interviewer->name }}"readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="applicant_name" class="col-md-3 col-form-label">Name</label>
                                <div class="col-md-9">
                                    <input id="feedback_name" type="text" class="form-control form-control-lg"
                                        name="feedback_name" required>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="feedback" class="col-md-3 col-form-label">Feedback/Comments</label>
                                <div class="col-md-9">
                                    <textarea id="feedback_text" class="form-control form-control-lg" name="feedback_text" rows="5" maxlength="500"
                                        required></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-9 offset-md-3">
                                    <button type="submit" class="btn btn-lg"
                                        style="background-color: #763B88; color: white;">
                                        Submit Feedback
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('feedbackForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop normal form submission

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Feedback Submitted!',
                                text: 'Thank you for your feedback.',
                                confirmButtonColor: '#763B88'
                            }).then(() => {
                                window.location.href = "{{ route('welcome') }}";
                            });
                        } else {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Submission failed.');
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!',
                            text: error.message || 'Something went wrong. Please try again.',
                            confirmButtonColor: '#763B88'
                        });
                    });
            });
        });
    </script>
@endsection
