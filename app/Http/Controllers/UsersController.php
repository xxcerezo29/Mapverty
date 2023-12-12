<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(){
        $columns = [
            '#',
            'Name',
            'Email',
            'Role',
            'Actions'
        ];
        $data_display = [
            ['data' => 'DT_RowIndex'],
            ['data' => 'name'],
            ['data' => 'email'],
            ['data' => 'role-display'],
            ['data' => 'Actions' , 'orderable' => false, 'searchable' => false],
        ];
        $roles = config('enums.roles');
        return view('users', compact('columns', 'data_display', 'roles'));
    }
}
