<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityDate extends Model
{
    protected $fillable = [
        'applicant_id',
        'available_date',
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
