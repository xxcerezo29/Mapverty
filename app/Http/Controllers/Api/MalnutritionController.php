<?php

namespace App\Http\Controllers\Api;

use App\Exports\MalnutritionStatusExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MalnutritionController extends Controller
{
    public function getMalnutritionStatus(){
        $students = \App\Models\Student::whereYear('created_at', date('Y'))->with('info')->whereHas('info', function ($query){
            $query->whereNotNull('weight')->whereNotNull('height');
        })->get();

        $bmiData = $students->map(function ($student){
            return $student->BMI();
        })->filter();

        $v = $bmiData->filter(function ($bmi){
            return $bmi < 15;
        })->count();
        $S = $bmiData->filter(function ($bmi){
            return $bmi >= 15 && $bmi < 16;
        })->count();
        $U = $bmiData->filter(function ($bmi){
            return $bmi >= 16 && $bmi < 18.5;
        })->count();
        $N = $bmiData->filter(function ($bmi){
            return $bmi >= 18.5 && $bmi < 25;
        })->count();
        $O = $bmiData->filter(function ($bmi){
            return $bmi >= 25 && $bmi < 30;
        })->count();
        $I = $bmiData->filter(function ($bmi){
            return $bmi >= 30 && $bmi < 35;
        })->count();
        $II = $bmiData->filter(function ($bmi){
            return $bmi >= 35 && $bmi < 40;
        })->count();
        $III = $bmiData->filter(function ($bmi){
            return $bmi >= 40;
        })->count();

        $labels = [
            'Very severely underweight',
            'Severely underweight',
            'Underweight',
            'Normal (healthy weight)',
            'Overweight',
            'Obese Class I (Moderately obese)',
            'Obese Class II (Severely obese)',
            'Obese Class III (Very severely obese)'];

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched malnutrition status',
            'labels' => $labels,
            'data' =>[
                'v' => $v,
                'S' => $S,
                'U' => $U,
                'N' => $N,
                'O' => $O,
                'I' => $I,
                'II' => $II,
                'III' => $III,
            ]
        ]);

    }

    public function getMalnutritionStatusDatatable(Request $request){

        $bmiSelector = $request->bmi_selector;

        $students = \App\Models\Student::whereYear('created_at', date('Y'))->with('info')->whereHas('info', function ($query){
            $query->whereNotNull('weight')->whereNotNull('height');
        })->get();

        $filtered = $students->filter(function ($student) use($bmiSelector) {
            $bmi = $student->BMI();

            switch ($bmiSelector){
                case 1:
                    return $bmi < 15;
                case 2:
                    return $bmi >= 15 && $bmi < 16;
                case 3:
                    return $bmi >= 16 && $bmi < 18.5;
                case 4:
                    return $bmi >= 18.5 && $bmi < 25;
                case 5:
                    return $bmi >= 25 && $bmi < 30;
                case 6:
                    return $bmi >= 30 && $bmi < 35;
                case 7:
                    return $bmi >= 35 && $bmi < 40;
                case 8:
                    return $bmi >= 40;
                default:
                    return true;
            }
        });

        return datatables()->of($filtered)->addIndexColumn()->addColumn('BMISTATUS', function ($student){
            return $student->BMI();
        })->addColumn('fullname', function ($student){
            return $student->getFullNameAttribute();
        })->addColumn('sectionString', function ($student){
            return config('enums.programs.'.$student->program).' '.$student->year.'-'.$student->section;
        })->addColumn('ACTION', function ($student){
            if(!auth()->user()->hasRole('Teacher')){
                return '<a href="/students/view/' . $student->student_number . '" class="btn btn-primary btn-sm">View</a>';
            }
            return '';
        })->rawColumns(['ACTION'])->toJson();
    }

    public function exportMalnutritionStatus(){
        return (new \App\Exports\MalnutritionStatusExport())->download('malnutrition_status.xlsx');
    }
}
