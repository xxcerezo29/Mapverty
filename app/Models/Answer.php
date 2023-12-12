<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'question_id',
        'answer',
    ];

    public function question(){
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
    public function student(){
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
}
