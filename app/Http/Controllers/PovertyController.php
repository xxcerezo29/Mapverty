<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PovertyController extends Controller
{
    public function index(){
        $columns = [
            '#',
            'Student Number',
            'Name',
            'Section',
            'Poverty Status',
            'Action'
        ];

        $data_display = [
            ['data' => 'DT_RowIndex'],
            ['data' => 'student_number'],
            ['data' => 'fullname'],
            ['data' => 'sectionString'],
            ['data' => 'povertyStatus'],
            ['data' => 'ACTION' , 'orderable' => false, 'searchable' => false],
        ];

        return view('poverty', compact(['columns', 'data_display']));
    }
}
