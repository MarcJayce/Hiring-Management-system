<?php

use App\Http\Controllers\Apply\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apply\InternController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\InternCandidateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\InterviewScheduleController;
use App\Models\ApplicantDetails;
use App\Http\Controllers\InterviewSetController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
//Feedback Routes
Route::get('/feedback/{id}', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

//Job Listings and Application Routes
Route::get('/jobs', [JobController::class, 'availablePositions'])->name('jobs.list');
Route::get('/apply/intern/{id}', [InternController::class, 'create'])->name('apply.intern');
Route::get('/apply/employee/{id}', [EmployeeController::class, 'create'])->name('apply.employee');

Route::post('/apply/intern', [InternController::class, 'store'])->name('intern.store');
Route::post('/apply/employee', [EmployeeController::class, 'store'])->name('employee.store');

Route::post('/update-data', [HomeController::class, 'updateData'])->name('dashboard.update-data');

Route::get('/thank-you', function () {
    return view('thankyou');
})->name('thankyou');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('/dashboard/update-data', [HomeController::class, 'updateData'])->name('dashboard.update-data');
    Route::get('/dashboard/filter', [HomeController::class, 'filterDashboardData'])->name('dashboard.filter');

    //View Application Form for Admins
    Route::get('/apply/intern', [InternController::class, 'create'])->name('intern.create');
    Route::get('/apply/employee', [EmployeeController::class, 'create'])->name('employee.create');

    //Candidates Routes
    Route::get('/candidates', [InternCandidateController::class, 'index'])->name('candidates.index');
    Route::get('/candidates/{id}', [internCandidateController::class, 'show'])->name('candidates.view');
    Route::post('/candidates/{id}/update-status', [InternCandidateController::class, 'updateStatus'])->name('candidates.updateStatus');
    Route::post('/candidates/{id}/shortlist', [InternCandidateController::class, 'shortlist'])->name('candidates.shortlist');
    Route::post('/candidates/{id}/for-interview', [InternCandidateController::class, 'moveToInterview'])->name('candidates.moveToInterview');
    Route::post('/candidates/{id}/undo', [InternCandidateController::class, 'undoStatus'])->name('candidates.undo');
    Route::post('/candidates/{id}/mark-completed', [InternCandidateController::class, 'markAsCompleted']);
    Route::post('/candidates/{id}/make-offer', [InternCandidateController::class, 'makeOffer']);
    Route::post('/candidates/{id}/hire', [internCandidateController::class, 'hireCandidate']);
    Route::post('/candidates/{id}/reject', [InternCandidateController::class, 'rejectCandidate']);
    Route::get('/search-candidates', [internCandidateController::class, 'search']);
    Route::get('/candidates/{id}/schedule-interview', [InternCandidateController::class, 'showScheduleForm'])
        ->name('candidates.schedule-interview');
    Route::post('/candidates/{id}/schedule-interview', [InternCandidateController::class, 'storeSchedule'])
        ->name('candidates.schedule-interview.store');
    Route::post('/candidates/{id}/schedule-interview', [InternCandidateController::class, 'storeSchedule']);
    Route::get('/sidebar/candidates', [InternCandidateController::class, 'index'])->name('sidebar.candidates');

    //View Feedback Routes
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/view', [FeedbackController::class, 'show'])->name('feedback.show');

    //Calendar Routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/fetch-events', [CalendarController::class, 'fetchEvents'])->name('calendar.fetchEvents');

    //Job Position Routes
    Route::get('/add-position', [JobController::class, 'create'])->name('position.create');
    Route::post('/add-position', [JobController::class, 'store'])->name('job_positions.store');
    Route::get('/job_positions/{id}/edit', [JobController::class, 'edit'])->name('job_positions.edit');
    Route::put('/job_positions/{id}', [JobController::class, 'update'])->name('job_positions.update');
    Route::delete('/job_positions/{id}', [JobController::class, 'destroy'])->name('job_positions.destroy');
    Route::get('/job_positions/{id}', [JobController::class, 'show'])->name('job_positions.show');
    Route::get('/vacancies/interns', [JobController::class, 'interns'])->name('vacancies.interns');
    Route::get('/vacancies/employees', [JobController::class, 'employees'])->name('vacancies.employees');

    //Auth Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/api/users', [UserController::class, 'getAll']);
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    // Interview Routes
    Route::post('/candidates/{candidateId}/send-interview-invite', [InternCandidateController::class, 'sendInterviewInvite']);
    Route::get('/interviews/{id}/edit', [InterviewScheduleController::class, 'edit'])->name('interviews.edit');
    Route::put('/interviews/{id}', [InterviewScheduleController::class, 'update'])->name('interviews.update');

    Route::get('/schedule-interview', [InterviewScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule-interview', [InterviewScheduleController::class, 'store'])->name('interviews.schedule');
    Route::get('/api/applicants/{id}', function ($id) {
        return ApplicantDetails::with(['jobPosition', 'interviewAvailability'])->findOrFail($id);
    });
    Route::put('/interview/{id}/update-outcome', [InterviewScheduleController::class, 'updateOutcome'])->name('interview.updateOutcome');


    Route::delete('/interviews/{id}', [InterviewController::class, 'destroy'])->name('interviews.destroy');
    Route::get('/interviews/scheduled', [InterviewController::class, 'scheduled'])->name('interviews.scheduled');
    Route::get('interviews/conduct/{id}', [InterviewController::class, 'create'])->name('interviews.conduct');
    Route::post('interview/fetch-questions', [InterviewController::class, 'fetchQuestions'])->name('interview.fetchQuestions');
    Route::post('/interviews/conduct/{id}/submit', [InterviewController::class, 'store'])->name('interview.store');
    Route::get('/schedule-interview/{id}', [InterviewScheduleController::class, 'createWithApplicant'])->name('interviews.schedule.withApplicant');
    Route::get('/interview/{id}/view', [InterviewController::class, 'showInterviewResult'])->name('interview.view');
    Route::get('/interviews/completed', [InterviewController::class, 'showCompletedInterviews'])->name('interviews.completed');
    Route::prefix('interviews')->group(function () {
        Route::get('/', [InterviewSetController::class, 'index'])->name('interviews.index');
        Route::resource('sets', InterviewSetController::class)->names([
            'index' => 'interviews.sets.index',
            'store' => 'interviews.sets.store',
            'destroy' => 'interviews.sets.destroy'
        ]);
        Route::post('questions', [InterviewSetController::class, 'storeQuestion'])->name('interviews.questions.store');
        Route::put('questions/{id}', [InterviewSetController::class, 'update'])->name('interviews.questions.update');
        Route::delete('questions/{question}', [InterviewSetController::class, 'destroyQuestion'])->name('interviews.questions.destroy');
    });

    Route::post('/candidates/{candidateId}/send-hire-email', [InternCandidateController::class, 'sendHireEmail']);
    Route::post('/candidates/{id}/send-rejection-email', [InternCandidateController::class, 'sendRejectionEmail'])->name('candidate.rejectEmail');
    Route::post('/candidates/{id}/offer-accepted', [InternCandidateController::class, 'markOfferAccepted'])->name('candidates.offerAccepted');
    Route::post('/candidates/{id}/reconsider', [InternCandidateController::class, 'reconsider']);
    Route::put('/candidates/{id}/reject-by-applicant', [InternCandidateController::class, 'rejectByApplicant'])->name('candidates.rejectByApplicant');

    Route::get('/feedbacks/export', [FeedbackController::class, 'export'])->name('feedbacks.export');
});
