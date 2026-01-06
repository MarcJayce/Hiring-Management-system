<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ApplicantDetails;

class InterviewSchedules extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'type',
        'date',
        'time',
        'duration',
        'location',
        'user_id',
        'round',
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluationForm()
    {
        return $this->hasOne(EvaluationForm::class, 'interview_schedule_id');
    }

    public function interviewAnswers()
    {
        return $this->hasMany(InterviewAnswer::class, 'interview_schedule_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
