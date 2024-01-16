<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Answer;
use App\Models\Municipality;
use App\Models\Question;
use App\Models\Student;
use Illuminate\Http\Request;

class PovertyController extends Controller
{
    public function getPovertyStatus(Request $request){
        $students = \App\Models\Student::whereYear('created_at', "2023")
            ->get();

        $data = $students->filter(function ($student) use ($request){
            if($request->poverty_status === 'Below'){
                return $student->povertyStatus() === 'Below Poverty Line';
            }else if($request->poverty_status === 'Above'){
                return $student->povertyStatus() === 'Above Poverty Line';
            }else{
                return $student->povertyStatus() !== 'Empty';
            }
        });
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('fullname', function ($student){
                return $student->getFullNameAttribute();
            })
            ->addColumn('sectionString', function ($student){
                return config('enums.programs.'.$student->program).' '.$student->year.'-'.$student->section;
            })
            ->addColumn('povertyStatus', function ($student){
                return $student->povertyStatus();
            })
            ->addColumn('ACTION', function ($student){
                $btn = '';
                if(!auth()->user()->hasRole('Teacher')) {
                    $btn = '<div data-id="' . $student->id . '"> <a href="/students/view/' . $student->student_number . '" class="edit btn btn-info btn-sm">View</a></div>';
                }

                return $btn;
            })
            ->rawColumns(['ACTION'])
            ->toJson();
    }
    public function getPovertyStatusDoughnut(){
        $students = \App\Models\Student::whereYear('created_at', "2023")
            ->get();

        $belowPoverty = $students->filter(function ($student){
            return $student->povertyStatus() === 'Below Poverty Line';
        })->count();

        $abovePoverty = $students->filter(function ($student){
            return $student->povertyStatus() === 'Above Poverty Line';
        })->count();

        if($belowPoverty == 0 && $abovePoverty == 0){
            $empty = true;
        }else{
            $empty = false;
        }



        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched poverty status',
            'overall' => $students->count(),
            'empty' => $empty,
            'labels' => [
                'Below Poverty Line',
                'Above Poverty Line',
            ],
            'data' =>[
                'belowPoverty' => $belowPoverty,
                'abovePoverty' => $abovePoverty,
            ]
        ]);

    }

    public function povertyStatusLineChart(){
    $question = Question::where('question', 'Family Monthly income')->first();

    if (!$question) {
        return response()->json([
            'status' => 'error',
            'message' => 'Question not found',
            'data' => []
        ]);
    }
        $data = Answer::select('created_at')->where('question_id', $question->id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->groupBy(function ($answer) {
                return $answer->created_at->format('Y');
            });
        $labels = $data->keys()->toArray();

        $belowPoverty = [];
        $abovePoverty = [];

        foreach ($labels as $label) {
            $students = \App\Models\Student::whereYear('created_at', $label)
                ->get();

            $belowPoverty[] = $students->filter(function ($student) {
                return $student->povertyStatus() === 'Below Poverty Line';
            })->count();

            $abovePoverty[] = $students->filter(function ($student){
                return $student->povertyStatus() === 'Above Poverty Line';
            })->count();

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched poverty status',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Below Poverty Line',
                        'data' => $belowPoverty,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                    [
                        'label' => 'Above Poverty Line',
                        'data' => $abovePoverty,
                        'fill' => false,
                        'lineTension' => 0,
                    ],
                ]
            ]
        ]);
    }

    public function povertyindices(Request $request){
        $question = Question::where('question', 'Family Monthly income')->first();
        if(!$question){
            return response()->json([
                'status' => 'error',
                'message' => 'Question not found'
            ]);
        }
        $answers = Answer::whereYear('created_at', $request->year_selector)->where('question_id', $question->id)->get();
        $allStudentcount = Student::select('id')->whereYear('created_at', $request->year_selector)->count();

        $grouped = $answers->groupBy('student.info.Address.municipality');

        $below = $grouped->map(function ($item, $key) use ($allStudentcount) {

            $municipality = Municipality::select('adm3_en')->where('adm3_pcode', $key)->first();
            $belowCount = $item->filter(function ($item) {
                return $item->student->povertyStatus() === 'Below Poverty Line';
            })->count();
            $totalPer = round(($item->count()/$allStudentcount) * 100, 2);

            return[
                'municipality' => $municipality->adm3_en?? 'City/Municipality not found',
                'overall' => $allStudentcount,
                'count' => $belowCount,
                'totalPer' => $totalPer,
                'total' => $item->count(),
            ];

        });

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully fetched poverty indices',
            'data' => array_values($below->toArray())
        ]);
    }

    public function exportPovertyStatus(){
        return (new \App\Exports\PovertyStatusExport())->download('poverty-status.xlsx');
    }
}
