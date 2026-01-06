<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;
    protected $table = 'employee_educations'; 
    protected $fillable = [
        'applicant_id',
        'degree_earned',
        'university_name',
        'graduation_date',
        'certifications',
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
