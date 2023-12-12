<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
//
    public function getRegions(Request $request){

        $options = $request->forSelect?? false;

        if($options){
            $region = Region::select('gid', 'adm1_en', 'adm1_pcode')->get();
        }else{
            $region = Region::all();
        }

        return response()->json($region);
    }
}
