<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class EvaluationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_schedule_id',
        'overall_impression_summary',
        'strengths',
        'areas_for_improvement',
        'technical_assessment',
        'cultural_fit',
        'rating_score',
        'expected_salary',
        'follow_up_actions',
        'overall_rating',
        'notes',
    ];

    public function interviewSchedule()
    {
        return $this->belongsTo(InterviewSchedules::class);
    }
}
