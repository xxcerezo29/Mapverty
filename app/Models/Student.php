<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable    ;
    protected $guard = 'students';

    public $fillable = [
        'lrn',
        'student_number',
        'email',
        'year',
        'section',
        'program',
        'personal_information'
    ];

    public function getFullNameAttribute(){
        return $this->info->firstname . ' ' . $this->info->middlename . ' ' . $this->info->lastname;
    }

    public function info(){
        return $this->hasOne(PersonalInformation::class, 'id', 'personal_information');
    }
    public function household(){
        return $this->hasOne(HouseholdInformation::class, 'student', 'id');
    }

    public function address(){
        return $this->hasOneThrough(Address::class, PersonalInformation::class, 'id', 'id', 'personal_information', 'address');
    }

    public function BMI(){
        if($this->info->height == 0 || $this->info->height == null){
            return 0;
        }
        $height = $this->info->height * 0.01;
        $bmi = $this->info->weight / ($height * $height);
        return $bmi;
    }
    public function firstGeneration(){
        $question = Question::where('question', 'Are you a first Generation? (first to attend college among siblings)')->first();
        if(!$question){
            return 'Empty';
        }
        $answer = $this->answers->where('question_id', $question->id)->first();
        if(!$answer){
            return 'Empty';
        }

        if($answer->answer == 'Yes'){
            return 'Yes';
        }else {
            return 'No';
        }
    }
    public function address_string(){
        if($this->info->address == null){
            return '---';
        }
        $barangay = Barangay::select('adm4_en')->where('adm4_pcode', $this->info->Address->barangay)->first();
        $city = Municipality::select('adm3_en')->where('adm3_pcode', $this->info->Address->municipality)->first();
        $province = Province::select('adm2_en')->where('adm2_pcode', $this->info->Address->province)->first();
        $region = Region::select('adm1_en')->where('adm1_pcode', $this->info->Address->region)->first();

        return $barangay->adm4_en . ', ' . $city->adm3_en . ', ' . $province->adm2_en . ', ' . $region->adm1_en;
    }

    public function answers(){
        return $this->hasMany(Answer::class, 'student_id', 'id');
    }

    public function povertyStatus(){
        $question = Question::where('question', 'Family Monthly income')->first();
        if(!$question){
            return 'Empty';
        }
        $answer = $this->answers->where('question_id', $question->id)->first();
        if(!$answer){
            return 'Empty';
        }

        if($answer->answer <= '12082'){
            return 'Below Poverty Line';
        }else {
            return 'Above Poverty Line';
        }
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
