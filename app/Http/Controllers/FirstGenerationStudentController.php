<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirstGenerationStudentController extends Controller
{
    public function index()
    {
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
            ['data' => 'sectionString'],
            ['data' => 'ACTION' , 'orderable' => false, 'searchable' => false],
        ];
        return view('firstgeneration', compact(['columns', 'data_display']));
    }
}
