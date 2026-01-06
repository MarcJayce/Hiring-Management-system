@extends('layouts.app')

@section('title', 'Scheduled Interviews')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="mb-4">Conduct Interview</h1>
                @if ($scheduledInterviews->isEmpty())
                    <p>No interviews scheduled.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Applicant Name</th>
                                <th>Position</th>
                                <th>Interview Date</th>
                                <th>Interviewer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scheduledInterviews as $interview)
                                <tr>
                                    <td>{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}</td>
                                    <td>{{ $interview->applicant->jobPosition->position_title }}</td>
                                    <!-- assuming you have this -->
                                    <td>{{ \Carbon\Carbon::parse($interview->date)->format('F d, Y') }}
                                        {{ $interview->time }}</td>
                                    <td>{{ $interview->interviewer->name }}</td>
                                    <td>
                                        <a href="{{ route('interviews.conduct', $interview->id) }}"
                                            class="btn btn-primary btn-sm">
                                            Conduct Interview
                                        </a>
                                        <a href="{{ route('interviews.edit', $interview->id) }}"
                                            class="btn btn-warning btn-sm">
                                            Reschedule
                                        </a>
                                        <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure you want to delete this interview?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
@endsection
