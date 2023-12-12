<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student',
        'father_name',
        'father_occupation',
        'father_education',
        'father_contact_number',
        'mother_name',
        'mother_occupation',
        'mother_education',
        'mother_contact_number',
    ];
}
