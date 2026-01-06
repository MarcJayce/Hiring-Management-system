@extends('layouts.app')
<style>
    .table th {
        background-color: #f8f9fa;
        white-space: nowrap;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .table td:nth-child(1),
    .table td:nth-child(2) {
        width: 120px;
    }

    .table td:nth-child(4) {
        white-space: normal;
        max-width: 400px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 500;
    }
</style>


@section('content')
    <div class="container-fluid px-4">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        Submitted Feedbacks
                    </div>

                    <div class="card-body p-4">
                        @if ($feedbacks->isEmpty())
                            <p>No feedbacks submitted yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Interview Round</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Name</th>
                                            <th>Interviewer</th>
                                            <th>Feedback / Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($feedbacks as $feedback)
                                            <tr>
                                                <td>{{ $feedback->interview->round ?? 'N/A' }} </td>
                                                <td>{{ \Carbon\Carbon::parse($feedback->feedback_date)->format('F j, Y') }}
                                                </td>
                                                <td>{{ $feedback->interview->time ?? 'N/A' }}
                                                </td>
                                                <td>{{ $feedback->feedback_name }}</td>
                                                <td>{{ $feedback->interview->interviewer->name ?? 'N/A' }}</td>
                                                <td
                                                    style="text-align: justify; max-width: 400px; max-height: 100px; overflow-y: auto; white-space: normal;">
                                                    {{ $feedback->feedback_text }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <div class="mb-3 text-end">
                            <a href="{{ route('feedbacks.export') }}" class="btn btn-success">
                                Export to Excel
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
