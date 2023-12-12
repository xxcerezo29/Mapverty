<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $students = \App\Models\Student::all();
        $studentsCount = $students->count();

        $users = \App\Models\User::count();

        $respondent = \App\Models\Student::whereHas('answers')->count();

        $firstGeneration =$students->filter(function($student){
            return $student->firstGeneration() == 'Yes';
        })->count();




        $povertyindex = number_format(($students->filter(function($student){
            return $student->povertyStatus() == 'Below Poverty Line';
        })->count()/ $students->count()) * 100 , 2);



        return view('index', compact('studentsCount', 'users', 'respondent', 'firstGeneration', 'povertyindex'));
    }
}
