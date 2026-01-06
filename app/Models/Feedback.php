<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'interview_id',
        'feedback_date',
        'feedback_name',
        'feedback_text'
    ];

    public function interview()
    {
        return $this->belongsTo(InterviewSchedules::class);
    }
}
