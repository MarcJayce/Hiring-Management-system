<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSpecifics extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'desired_salary',
        'available_date',
        'job_interest',
        'why_hire'
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
