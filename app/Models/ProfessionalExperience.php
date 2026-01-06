<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'company_name',
        'job_title',
        'start_date',
        'end_date',
        'responsibilities',
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}

