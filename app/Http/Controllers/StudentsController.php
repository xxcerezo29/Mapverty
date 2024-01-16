<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index(){
        $columns = [
            '#',
            'LRN',
            'Student Number',
            'Name',
            'Program Year-Section',
            'Actions'
        ];
        $data_display = [
            ['data' => 'DT_RowIndex'],
            ['data' => 'lrn'],
            ['data' => 'student_number'],
            ['data' => 'fullname'],
            ['data' => 'Sections'],
            ['data' => 'Actions' , 'orderable' => false, 'searchable' => false],
        ];

        $sex = config('enums.sex');
        $gender = config('enums.gender');

        $civilStatus = config('enums.civilstatus');
        $nationality = config('nationality');

        $programs = config('enums.programs');
        $years = config('enums.years');
        $sections = config('enums.sections');

        return view('students' , compact(['columns', 'data_display', 'sex', 'civilStatus', 'gender', 'nationality', 'programs', 'years', 'sections']));
    }

    public function edit($student_number){
        $student = Student::where('student_number',$student_number)->first();

        $sex = config('enums.sex');
        $gender = config('enums.gender');

        $civilStatus = config('enums.civilstatus');
        $nationality = config('nationality');

        $programs = config('enums.programs');
        $years = config('enums.years');
        $sections = config('enums.sections');

        return view('pages.edit-students', compact(['student', 'sex', 'gender', 'civilStatus', 'nationality', 'programs', 'years', 'sections']));

    }

    public function view($student_number){
        $student = Student::where('student_number',$student_number)->first();
        return view('pages.view-student', compact('student'));
    }
}
