<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_title',
        'department',
        'work_setup',
        'reports_to',
        'job_duration',
        'work_hours',
        'compensation',
        'position_description',
        'key_responsibilities',
        'benefits',
        'start_date',
        'end_date',
        'availability',
        'status',
        'application_type'
    ];
}