<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $fillable = [
        'region',
        'province',
        'municipality',
        'barangay',
    ];
    public function info(){
        return $this->hasOne(PersonalInformation::class, 'address', 'id');
    }
}
