<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    protected $fillable = ['name'];

    public function interviewQuestions()
    {
        return $this->hasMany(InterviewQuestion::class);
    }
}
