<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewAnswer extends Model
{
    protected $fillable = ['interview_question_id', 'interview_schedule_id', 'answer'];

    public function interviewQuestion()
    {
        return $this->belongsTo(InterviewQuestion::class);
    }

    public function interviewSchedule()
    {
        return $this->belongsTo(InterviewSchedules::class);
    }
}
