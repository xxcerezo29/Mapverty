<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MalnutritionController extends Controller
{
    public function index()
    {
        $columns = [
            '#',
            'Student Number',
            'Name',
            'Section',
            'BMI Status',
            'Action'
        ];
        $param = [
            'bmi_selector' => 0,
        ];
        $data_display = [
            ['data' => 'DT_RowIndex'],
            ['data' => 'student_number'],
            ['data' => 'fullname'],
            ['data' => 'sectionString'],
            ['data' => 'BMISTATUS'],
            ['data' => 'ACTION' , 'orderable' => false, 'searchable' => false],
        ];
        return view('malnutrition', compact(['columns', 'data_display', 'param']));
    }
}
