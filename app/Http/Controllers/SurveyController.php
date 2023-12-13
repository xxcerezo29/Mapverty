<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:students', ['except' => ['index', 'email', 'otp', 'surveyNew', 'changeEmail']] , 'verified');
    }

    public function index(){
        return view('pages.student-index');
    }
    public function otp(Request $request){
        $student = Student::where('student_number', $request->student_number)->firstOrFail();
        if($student->answers()->count() > 0){
            return redirect()->route('survey.index')->with('error', 'Student already answered the survey');
        }


        return view('pages.student-otp', compact('student'));
    }

    public function survey(Request $request){
        $sex = config('enums.sex');
        $gender = config('enums.gender');

        $civilStatus = config('enums.civilstatus');
        $nationality = config('nationality');

        $programs = config('enums.programs');
        $years = config('enums.years');
        $sections = config('enums.sections');

        $questions = \App\Models\Question::all();

        $occupation = config('enums.occupation');
        $education = config('enums.educational_attainment');



        return view('pages.student-survey', compact([ 'occupation', 'education', 'programs', 'years', 'sex', 'gender','civilStatus', 'nationality', 'questions']));
    }

    public function surveyNew(Request $request){
        $sex = config('enums.sex');
        $gender = config('enums.gender');

        $civilStatus = config('enums.civilstatus');
        $nationality = config('nationality');

        $programs = config('enums.programs');
        $years = config('enums.years');
        $sections = config('enums.sections');

        $questions = \App\Models\Question::all();

        $occupation = config('enums.occupation');
        $education = config('enums.educational_attainment');


        return view('pages.student-survey-new', compact(['education','programs', 'years', 'sex', 'gender','civilStatus', 'nationality', 'questions', 'occupation']));
    }

    public function email(Request $request){
        if($request->has('student_number')){
            $student = \App\Models\Student::where('student_number', $request->student_number)->firstOrFail();
            if($student->email == null && $student->lrn == null){
                return view('pages.student-email', compact('student'));
            }else{
                return redirect()->route('survey.index')->with('error', 'Student had an email or LRN');
            }

        }else {
            return redirect()->route('survey.index');
        }
    }
    public function changeEmail(Request $request){
        return view('pages.student-change-email');
    }
}
