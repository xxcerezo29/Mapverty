<?php

namespace App\Http\Controllers\Api;

use App\Exports\StudentListExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudent;
use App\Models\Address;
use App\Models\PersonalInformation;
use App\Models\Question;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    public function getStudents()
    {
        $students = Student::all();
        $students->load('info');
        $students->load('address');

        return datatables()->of($students)
            ->addIndexColumn()
            ->addColumn('fullname', function($row){
                return $row->getFullNameAttribute();
            })
            ->addColumn('Sections', function ($row){
                return config('enums.programs.'.$row->program).' '.$row->year.'-'.$row->section;
            })
            ->addColumn('Actions', function($row){
                $btn = '';
                if(!auth()->user()->hasRole('Teacher'))
                {
                    $btn = '<div data-id="'.$row->id.'">  <a href="/students/'.$row->student_number.'" class="edit btn btn-primary btn-sm">Edit</a> <a href="/students/view/'.$row->student_number.'" class="edit btn btn-info btn-sm">View</a> <button onclick="remove(`'.$row->student_number.'`)" class="delete btn btn-danger btn-sm">Delete</button></div>';
                }

                return $btn;
            })->rawColumns(['Actions'])
            ->toJson();
    }

    
    public function storeStudent(StoreStudent $request)
    {
        DB::beginTransaction();
        try {
            $address = Address::create([
                'region' => $request->region,
                'province' => $request->province,
                'municipality' => $request->city,
                'barangay' => $request->barangay,
            ]);

            $info = PersonalInformation::create([
                'firstname' => $request->first_name,
                'middlename' => $request->middle_name,
                'lastname' => $request->last_name,
                'birthdate' => $request->birthdate,
                'sex' => $request->sex,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'height' => $request->height,
                'civilstatus' => $request->civil_status,
                'cellphone' => $request->phone,
                'nationality' => $request->nationality,
                'address' => $address->id,
            ]);

            $student = Student::create([
                'lrn' => $request->lrn,
                'student_number' => $request->student_number,
                'email' => $request->email,
                'program' => $request->program,
                'year' => $request->year,
                'section' => $request->section,
                'personal_information' => $info->id,
            ]);

            DB::commit();

        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['message' => 'Student saved successfully.', 'data' => $student, 'title' => 'Success']);
    }

    public function removeStudent($student_number)
    {
        try {
            $student = Student::where('student_number', $student_number)->first();
            $student->delete();
        }catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        return response()->json(['message' => 'Student deleted successfully.', 'title' => 'Success']);
    }

    public function exportStudents(){
        return (new StudentListExport())->download('students.xlsx');
    }
}
