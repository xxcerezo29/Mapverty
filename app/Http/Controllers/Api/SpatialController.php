<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SpatialController extends Controller
{
    public function index(Request $request){
        if($request->layer == 'municipality'){
            $municipalities = \App\Models\Municipality::where('adm1_pcode', 'PH020000000')->get();
            $data = new \App\Http\Resources\MunicipalitiesCollection($municipalities);
            return response()->json([
                'status' => 'success',
                'title' => 'Below Poverty Line',
                'mode' => 'municipality',
                'data' => json_encode($data),
            ]);
        }elseif ($request->layer == 'barangay') {
            if($request->municipality == null)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please select a municity'
                ])->setStatusCode(404);

            $barangays = \App\Models\Barangay::where('adm3_pcode', $request->municipality)->get();
            $data = new \App\Http\Resources\BarangayCollection($barangays);
            return response()->json([
                'status' => 'success',
                'title' => 'Below Poverty Line',
                'mode' => 'barangay',
                'data' => json_encode($data),
            ]);
        }
    }
}
