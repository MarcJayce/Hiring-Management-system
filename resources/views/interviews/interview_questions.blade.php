@extends('layouts.app')

@section('title', 'Interview Questions')

@section('content')
    <div class="container">
        <h1>Interview Questions</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSetModal">
                Add New Set
            </button>
        </div>

        <ul class="nav nav-tabs" id="interviewTab" role="tablist">
            @foreach ($interviewSets as $set)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="set{{ $set->id }}-tab" data-toggle="tab"
                        href="#set{{ $set->id }}" role="tab" aria-controls="set{{ $set->id }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $set->name }} ({{ $set->category ?? 'No Category' }})
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="interviewTabContent">
            @foreach ($interviewSets as $set)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="set{{ $set->id }}"
                    role="tabpanel" aria-labelledby="set{{ $set->id }}-tab">
                    <div class="d-flex mb-3 gap-2">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#addQuestionModal{{ $set->id }}">
                            Add Interview Question
                        </button>
                        <form action="{{ route('interviews.sets.destroy', $set->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this set?');">Delete
                                {{ $set->name }}</button>
                        </form>
                    </div>

                    <!-- Behavioral Questions Table -->
                    <h4>Behavioral Questions</h4>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $setIdStr = (string)$set->id; @endphp
                            @if (isset($behavioralQuestions[$setIdStr]))
                                @foreach ($behavioralQuestions[$setIdStr] as $question)
                                    <tr>
                                        <td class="text-start">{{ $question->question_text }}</td>
                                        <td>
                                            <!-- Inside each question row -->
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#editQuestionModal{{ $question->id }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('interviews.questions.destroy', $question->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <!-- Edit Question Modal for Question ID {{ $question->id }} -->
                                    <div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editQuestionModalLabel{{ $question->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('interviews.questions.update', $question->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editQuestionModalLabel{{ $question->id }}">Edit Interview
                                                            Question</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span>&times;</span></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="set_id" value="{{ $set->id }}">

                                                        <div class="form-group">
                                                            <label for="editQuestionType{{ $question->id }}">Question
                                                                Type</label>
                                                            <select name="question_type"
                                                                id="editQuestionType{{ $question->id }}"
                                                                class="form-control" required>
                                                                <option value="Behavioral"
                                                                    {{ $question->question_type === 'Behavioral' ? 'selected' : '' }}>
                                                                    Behavioral</option>
                                                                <option value="Technical"
                                                                    {{ $question->question_type === 'Technical' ? 'selected' : '' }}>
                                                                    Technical</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label
                                                                for="editQuestionText{{ $question->id }}">Question</label>
                                                            <input type="text" name="question_text"
                                                                id="editQuestionText{{ $question->id }}"
                                                                class="form-control" value="{{ $question->question_text }}"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No behavioral questions found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    <!-- Technical Questions Table -->
                    <h4 id="technicalQuestionsTitle{{ $set->id }}">Technical Questions
                        ({{ $set->category ?? 'No Category' }})</h4>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($technicalQuestions[$setIdStr]))
                                @foreach ($technicalQuestions[$setIdStr] as $question)
                                    <tr>
                                        <td>{{ $question->question_text }}</td>
                                        <td>
                                            <!-- Inside each question row -->
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#editQuestionModal{{ $question->id }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('interviews.questions.destroy', $question->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <!-- Edit Question Modal for Question ID {{ $question->id }} -->
                                    <div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editQuestionModalLabel{{ $question->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('interviews.questions.update', $question->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="editQuestionModalLabel{{ $question->id }}">Edit Interview
                                                            Question</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span>&times;</span></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="set_id"
                                                            value="{{ $set->id }}">

                                                        <div class="form-group">
                                                            <label for="editQuestionType{{ $question->id }}">Question
                                                                Type</label>
                                                            <select name="question_type"
                                                                id="editQuestionType{{ $question->id }}"
                                                                class="form-control" required>
                                                                <option value="Behavioral"
                                                                    {{ $question->question_type === 'Behavioral' ? 'selected' : '' }}>
                                                                    Behavioral</option>
                                                                <option value="Technical"
                                                                    {{ $question->question_type === 'Technical' ? 'selected' : '' }}>
                                                                    Technical</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label
                                                                for="editQuestionText{{ $question->id }}">Question</label>
                                                            <input type="text" name="question_text"
                                                                id="editQuestionText{{ $question->id }}"
                                                                class="form-control"
                                                                value="{{ $question->question_text }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save
                                                            Changes</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No technical questions found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Modal for Adding a New Question for Set {{ $set->id }} -->
                <div class="modal fade" id="addQuestionModal{{ $set->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="addQuestionModalLabel{{ $set->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addQuestionModalLabel{{ $set->id }}">Add Interview
                                    Question to {{ $set->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addQuestionForm{{ $set->id }}"
                                    action="{{ route('interviews.questions.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="set_id" value="{{ $set->id }}">
                                    <div class="form-group">
                                        <label for="newQuestionType{{ $set->id }}">Question Type</label>
                                        <select name="question_type" id="newQuestionType{{ $set->id }}"
                                            class="form-control" required>
                                            <option value="Behavioral">Behavioral</option>
                                            <option value="Technical">Technical</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="newQuestion{{ $set->id }}">Question</label>
                                        <input type="text" name="question_text" id="newQuestion{{ $set->id }}"
                                            class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Question</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal for Adding a New Set -->
        <div class="modal fade" id="addSetModal" tabindex="-1" role="dialog" aria-labelledby="addSetModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSetModalLabel">Add New Set</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addSetForm" action="{{ route('interviews.sets.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="newSetName">Set Name</label>
                                <input type="text" name="set_name" id="newSetName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="newSetCategory">Category</label>
                                <select name="category" id="newSetCategory" class="form-control" required>
                                    <option value="Marketing">Marketing</option>
                                    <option value="IT">IT</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Add Set</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                // Handle form submission for adding a new set
                $('#addSetForm').submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var data = form.serialize();

                    $.post(url, data, function(response) {
                        $('#addSetModal').modal('hide');
                        $('#newSetName').val('');
                        $('#newSetCategory').val('Marketing');
                        location.reload();
                    }).fail(function(xhr) {
                        alert('Failed to add new set: ' + xhr.responseText);
                    });
                });

                // Handle form submission for adding a new question
                $('form[id^="addQuestionForm"]').submit(function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var url = form.attr('action');
                    var data = form.serialize();

                    $.post(url, data, function(response) {
                        form.closest('.modal').modal('hide');
                        form.find('input[type="text"]').val('');
                        form.find('select').val('Behavioral');
                        location.reload();
                    }).fail(function(xhr) {
                        alert('Failed to add new question: ' + xhr.responseText);
                    });
                });

                // Update technical questions title based on selected category
                $('#newSetCategory').on('change', function() {
                    var selectedCategory = $(this).val();
                    $('#technicalQuestionsTitle').text('Technical Questions (' + selectedCategory + ')');
                });
            });
        </script>
    @endsection
</div>
@endsection
