<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getRegions(Request $request){

        $options = $request->forSelect?? false;

        if($options){
            $region = Region::select('gid', 'adm1_en', 'adm1_pcode')->get();
        }else{
            $region = Region::all();
        }

        return response()->json($region);
    }

    public function getProvinces($adm1_pcode){
        $provinces = Province::select('gid', 'adm2_en', 'adm2_pcode')->where('adm1_pcode', $adm1_pcode)->get();
        return response()->json($provinces);
    }

    public function getMunicipalities($adm2_pcode){
        $municipalities = Municipality::select('gid', 'adm3_en', 'adm3_pcode')->where('adm2_pcode', $adm2_pcode)->get();
        return response()->json($municipalities);
    }

    public function getBarangays($adm3_pcode){
        $barangays = Barangay::select('gid', 'adm4_en', 'adm4_pcode')->where('adm3_pcode', $adm3_pcode)->get();
        return response()->json($barangays);
    }
}
