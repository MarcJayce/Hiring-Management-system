<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewQuestion extends Model
{
    protected $fillable = ['question_text', 'question_type', 'set_id'];

    public function interviewSet()
    {
        return $this->belongsTo(InterviewSet::class, 'set_id');
    }
}