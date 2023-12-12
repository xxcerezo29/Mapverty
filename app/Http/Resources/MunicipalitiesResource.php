<?php

namespace App\Http\Resources;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MunicipalitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $geojson = $this->select(DB::raw('ST_AsGeoJSON(geom) as geometry'))->where('gid', $this->gid)->first();
        $students = Student::with('info')
            ->with('info.Address')
            ->whereHas('info.Address', function ($query){
                $query->where('municipality', $this->adm3_pcode);
            })->get();

        $count = $students->filter(function ($student){
            return $student->povertyStatus() == 'Below Poverty Line';
        })->count();

        return [
            'type' => 'Feature',
            'geometry' => json_decode($geojson->geometry),
            'properties' => [
                'gid' => $this->gid,
                'shape_leng' => $this->shape_leng,
                'shape_area' => $this->shape_area,
                'adm3_en' => $this->adm3_en,
                'adm3_pcode' => $this->adm3_pcode,
                'adm3_ref' => $this->adm3_ref,
                'adm3alt1en' => $this->adm3alt1en,
                'adm3alt2en' => $this->adm3alt2en,
                'adm2_en' => $this->adm2_en,
                'adm2_pcode' => $this->adm2_pcode,
                'adm1_en' => $this->adm1_en,
                'adm1_pcode' => $this->adm1_pcode,
                'adm0_en' => $this->adm0_en,
                'adm0_pcode' => $this->adm0_pcode,
                'density' => $count,
                'students' => $students->count(),
            ],
        ];
    }
}
