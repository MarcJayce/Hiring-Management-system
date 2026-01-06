<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SkillsAbilities extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'technical_skills',
        'industry_knowledge',
        'soft_skills',
    ];

    protected $casts = [
        'technical_skills' => 'array',
        'soft_skills' => 'array',
    ];

    public function applicant()
    {
        return $this->belongsTo(ApplicantDetails::class, 'applicant_id');
    }
}
