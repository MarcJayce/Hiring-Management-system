@extends('layouts.app')

@section('title', 'Completed Interviews')

@section('content')
    <div class="container mt-5">
        <h1>Completed Interviews</h1>

        <!-- Search form -->
        <form method="GET" action="{{ route('interviews.completed') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by candidate name"
                    value="{{ request()->search }}">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
            <select name="sort_by" class="form-select" onchange="this.form.submit()" style="width: 250px;">
                <option value="" disabled {{ request('sort_by') ? '' : 'selected' }}>Sort By</option>
                <option value="first_name_asc" {{ request('sort_by') == 'first_name_asc' ? 'selected' : '' }}>
                    Applicant Name A-Z
                </option>
                <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>
                    Interview Date Ascending
                </option>
                <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>
                    Interview Date Descending
                </option>
            </select>
        </form>

        <!-- Table to display the interviews -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Applicant Name</th>
                    <th>Interview Round</th>
                    <th>Interviewer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Remarks</th>
                    <th>Actions</th>

                </tr>
            </thead>
            <tbody>
                @forelse($completedInterviews as $interview)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $interview->applicant->first_name }} {{ $interview->applicant->last_name }}</td>
                        <td>{{ $interview->round }}</td>
                        <td>{{ $interview->interviewer->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($interview->date)->format('F d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($interview->time)->format('h:i A') }}</td>
                        <td>{{ $interview->status }}</td>
                        <td>
                            <!-- Action buttons for View/Details -->
                            <a href="{{ route('interview.view', $interview->id) }}" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No completed interviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
