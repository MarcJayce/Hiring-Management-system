@extends('layouts.app')

@section('title', 'Employee Position')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Open Positions</h2>
        <a href="{{ route('position.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Position
        </a>
    </div>

    <div class="d-flex justify-content-between mb-3">

        <div>
            <label>Show
                <select id="entriesPerPage" class="form-select form-select-sm d-inline-block w-auto">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                entries
            </label>
        </div>


        <div>
            <input type="text" id="searchBox" class="form-control form-control-sm" placeholder="Search...">
        </div>
    </div>

    <table class="table table-striped table-bordered intern-table">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Position Title</th>
                <th>Description</th>
                <th>Availability</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="jobTableBody">
            @foreach($jobPositions as $job)
            <tr>
                <td>{{ ($jobPositions->currentPage() - 1) * $jobPositions->perPage() + $loop->iteration }}</td>s
                <td style="color: purple;">{{ $job->position_title }}</td>
                <td>{{ Str::limit($job->position_description, 200) }}</td>
                <td>{{ $job->availability }}</td>
                <td>
                    <span class="badge {{ $job->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $job->status }}
                    </span>
                </td>
                <td class="d-flex align-items-center gap-2">
                    <a href="{{ route('job_positions.show', $job->id) }}" class="btn btn-sm btn-success equal-btn m-1">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('job_positions.edit', $job->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>

                    <form action="{{ route('job_positions.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job position?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger equal-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $jobPositions->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const entriesSelect = document.getElementById('entriesPerPage');
        const searchBox = document.getElementById('searchBox');
        const tableBody = document.getElementById('jobTableBody');
        let originalRows = [...tableBody.rows];

        entriesSelect.addEventListener('change', function() {
            updateTable();
        });

        searchBox.addEventListener('keyup', function() {
            updateTable();
        });

        function updateTable() {
            const filter = searchBox.value.toLowerCase();
            const limit = parseInt(entriesSelect.value);
            let displayed = 0;

            [...tableBody.rows].forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(filter) && displayed < limit) {
                    row.style.display = "";
                    displayed++;
                } else {
                    row.style.display = "none";
                }
            });
        }

        updateTable();
    });
</script>

@endsection