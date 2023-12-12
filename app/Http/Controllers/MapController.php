<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $municipalities = \App\Models\Municipality::select('adm3_en', 'adm3_pcode')->where('adm1_pcode', 'PH020000000')->get()->filter(function ($municipality){
            return $municipality->address->count() > 0;
        });
        return view('map', compact('municipalities'));
    }
}
