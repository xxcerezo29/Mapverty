<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;


    protected $table = 'personal_informations';

    public $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'birthdate',
        'sex',
        'gender',
        'weight',
        'height',
        'civilstatus',
        'address',
        'cellphone',
        'nationality'
    ];

    public function Address(){
        return $this->hasOne(Address::class, 'id', 'address');
    }

    protected $casts = [
        'birthdate' => 'date',
    ];
}
