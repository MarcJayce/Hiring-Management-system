<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternshipSpecifics extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'internship_type',
        'desired_start_date',
        'hours_required',
        'weekly_availability',
        'internship_goals',
        'internship_interest',
        'why_hire'
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
