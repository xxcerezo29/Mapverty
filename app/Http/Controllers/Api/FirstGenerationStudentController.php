<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function Laravel\Prompts\password;

class FirstGenerationStudentController extends Controller
{
    public function getFirstGenerationStudent(Request $request)
    {
        $firstGenerationStudent = \App\Models\Student::whereYear('created_at', "2023")->whereHas('info', function ($query) use($request) {
            $query->where('sex', $request->sex);
        })->get()->filter(function ($student){
            return $student->firstGeneration() == 'Yes';
        });

        return datatables()->of($firstGenerationStudent)
            ->addIndexColumn()
            ->addColumn('fullname', function ($student){
                return $student->getFullNameAttribute();
            })->addColumn('sectionString', function ($student){
                return config('enums.programs.'.$student->program).' '.$student->year.'-'.$student->section;
            })->addColumn('ACTION', function ($student) {
                $btn = '';
                if (!auth()->user()->hasRole('Teacher')) {
                    $btn = '<div data-id="' . $student->id . '"> <a href="/students/view/' . $student->student_number . '" class="edit btn btn-info btn-sm">View</a></div>';
                }
                return $btn;
            })
            ->rawColumns(['ACTION'])
            ->toJson();
    }

    public function getFirstGenerationStudentChart(){
        $students = \App\Models\Student::whereYear('created_at', "2023")->get();
        $filteredStudents = $students->filter(function ($student){
            return $student->firstGeneration() == 'Yes';
        });

        $total = $filteredStudents->count();
        $male = $filteredStudents->where('info.sex', 1)->count();
        $female = $filteredStudents->where('info.sex', 2)->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched first generation student',
            'labels' => ['Male', 'Female'],
            'data' => [
                'Male' => $male,
                'Female' => $female,
            ]
        ]);
    }
    public  function exportFirstGenerationStudent()
    {
        return (new \App\Exports\FirstGenerationStudentExport())->download('First Generation Student.xlsx');
    }
}
