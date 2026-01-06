<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantDetails extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'status', 'position_id', 'certifications',];
    protected $casts = [
        'hiring_date' => 'date', 
    ];

    public function interviewAvailability()
    {
        return $this->hasMany(AvailabilityDate::class, 'applicant_id');
    }
    
    public function education()
    {
        return $this->hasOne(Education::class, 'applicant_id');
    }

    public function internship()
    {
        return $this->hasOne(InternshipSpecifics::class, 'applicant_id');
    }

    public function skillsExperience()
    {
        return $this->hasOne(SkillsExperiences::class, 'applicant_id');
    }

    public function otherInfo()
    {
        return $this->hasOne(OtherInformation::class, 'applicant_id');
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'position_id');
    }

    public function employeeEducation()
    {
        return $this->hasMany(EmployeeEducation::class, 'applicant_id');
    }

    public function skillsAbilities()
    {
        return $this->hasOne(SkillsAbilities::class, 'applicant_id');
    }

    public function getAvailabilityAttribute($value)
    {
        return ucfirst($value); 
    }

    public function setRequestedByAttribute($value)
    {
        $this->attributes['requested_by'] = trim($value); 
    }
    public function interviewSchedules()
    {
        return $this->hasMany(InterviewSchedules::class, 'applicant_id');
    }

    public function professionalExperience()
    {
        return $this->hasMany(ProfessionalExperience::class, 'applicant_id');
    }

    public function jobSpecifics()
    {
        return $this->hasOne(JobSpecifics::class, 'applicant_id');
    }

}
