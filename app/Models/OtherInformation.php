<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtherInformation extends Model
{
    use HasFactory;

    protected $fillable = ['applicant_id', 'resume', 'linkedin', 'referral_source'];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
