<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkillsExperiences extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_id', 'skills', 'volunteer_experience', 'part_time_jobs', 'extracurricular', 'portfolio_url'];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
