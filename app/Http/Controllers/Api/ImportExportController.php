<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Barangay;
use App\Models\HouseholdInformation;
use App\Models\Municipality;
use App\Models\PersonalInformation;
use App\Models\Province;
use App\Models\Question;
use App\Models\Region;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportExportController extends Controller
{
    public function import(Request $request){
        try {

            $this->validate($request, [
                'file' => 'required|mimes:csv'
            ]);

            $csvFile = $request->file('file');


            $csv = array_map('str_getcsv', file($csvFile));

            $header = array_shift($csv);


            if($request->source === '1'){
                try {
                    foreach ($csv as $row){
                        DB::beginTransaction();
                        $row = array_combine($header, $row);
                        $dateInfo = null;
                        if (!isset($row['surveys']))
                        {
                            throw new \ErrorException("You've seems selected/uploaded a different file...");
                        }
                        if (isset($row['created_at'])) {
                            $dateInfo = $row['created_at'];
                        }
                        $targetStudent = Student::whereYear('created_at', is_null($dateInfo)? date('Y') : date('Y', $row['created_at']))->where('student_number', $row['student_number'])->first();
                        if (is_null($targetStudent)) {
                            $region = Region::select('adm1_pcode')->where('adm1_en', $row['region'])->first();
                            $province = Province::select('adm2_pcode')->where('adm2_en', $row['province'])->first();
                            $municipality = Municipality::select('adm3_pcode')->where('adm3_en', $row['municipality'])->first();
                            $barangay = Barangay::select('adm4_pcode')->where('adm4_en', $row['barangay'])->first();

                            $address = Address::create([
                                'region' => $region->adm1_pcode,
                                'province' => $province->adm2_pcode,
                                'municipality' => $municipality->adm3_pcode,
                                'barangay' => $barangay->adm4_pcode,
                            ]);

                            $sex = array_search($row['sex'], config('enums.sex'));
                            $civilstatus = array_search($row['civilstatus'], config('enums.civilstatus'));
                            $program = array_search($row['program'], config('enums.programs'));
                            $year = array_search($row['year'], config('enums.years'));
                            $gender = array_search($row['gender'], config('enums.gender'));

                            $father_occupation = array_search($row['father_occupation'], config('enums.occupation'));
                            $mother_occupation = array_search($row['mother_occupation'], config('enums.occupation'));

                            $father_education = array_search($row['father_education'], config('enums.educational_attainment'));
                            $mother_education = array_search($row['mother_education'], config('enums.educational_attainment'));

                            $birthDate = \DateTime::createFromFormat('d-m-y', $row['birthdate']);
                            $info = PersonalInformation::create([
                                'firstname' => $row['firstname'],
                                'middlename' => $row['middlename'],
                                'lastname' => $row['lastname'],
                                'birthdate' => $birthDate->format('Y-m-d H:i:s'),
                                'sex' => $sex,
                                'gender' => $gender,
                                'address' => $address->id,
                                'civilstatus' => $civilstatus,
                                'cellphone' => $row['cellphone'],
                                'weight' => $row['weight'],
                                'height' => $row['height'],
                            ]);

                            $student = Student::create([
                                'student_number' => $row['student_number'],
                                'email' => $row['email'],
                                'program' => $program,
                                'year' => $year,
                                'section' => $row['section'],
                                'personal_information' => $info->id,
                            ]);

                            if(!is_null($dateInfo)){
                                $student->created_at = date('Y-m-d H:i:s', $dateInfo);
                                $student->save();
                            }

                            $questions = Question::with('choices')->get();
                            if($row['survey'] !== ''){
                                $answers = explode('-', $row['surveys']);
                                foreach ($answers as $answer){
                                    $answerss = json_decode($answer, true);
                                    if(isset($answerss['answer']) && $answerss['answer'] !== '')
                                    {
                                        $targetQuestion = $questions->where('question', $answerss['question'])->first();

                                        $answerSaved = $student->answers()->create([
                                            'question_id' => $targetQuestion->id,
                                            'answer' => $answerss['answer'],
                                        ]);

                                        $dateAnswer = [];
                                        if (isset($answerss['created_at']))
                                        {
                                            $answerSaved->created_at = date('Y-m-d H:i:s', $answerss['created_at']);
                                            $answerSaved->save();
                                        }
                                    }
                                }
                            }

                            HouseholdInformation::create([
                                'student' => $student->id,
                                'father_name' => $row['father_name'],
                                'father_occupation' => $father_occupation,
                                'father_education' => $father_education,
                                'father_contact_number' => $row['father_contact_number'],
                                'mother_name' => $row['mother_name'],
                                'mother_occupation' => $mother_occupation,
                                'mother_education' => $mother_education,
                                'mother_contact_number' => $row['mother_contact_number'],
                            ]);

                            DB::commit();
                        }

                    }
                } catch (\Exception $exception) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $exception->getMessage(),
                        'line' => $exception->getLine(),
                    ], 500);
                }

            }else if($request->source === '2'){

                foreach ($csv as $row) {
                    DB::beginTransaction();
                    $lastname = null;
                    $firstname = null;
                    $middlename = null;
                    $row = array_combine($header, $row);

                    $fullname = $row['NAME'];
                    $names = explode(', ', $fullname);
                    $lastname = $names[0];

                    $firsAndMiddle = count($names) >1? explode(' ', $names[1]) : $names[0];
                    $firsAndMiddleCount = is_array($firsAndMiddle)? count($firsAndMiddle) : 1;
                    if($firsAndMiddleCount > 1){
                        $middlename = end($firsAndMiddle);
                        array_pop($firsAndMiddle);
                    }
                    $firstname = is_array($firsAndMiddle)? implode(' ', $firsAndMiddle) : $firsAndMiddle;

                    $studentnumber = $row['ID NUMBER'];
                    $sex =  $row['SEX'] === 'M' ? 1: 2;
                    $program = array_search($row['PROGRAM'], config('enums.programs'));
                    $year = $row['YEAR'];
                    $section = $row['SECTION'];

                    $info = PersonalInformation::create([
                        'firstname' => $firstname,
                        'middlename' => $middlename,
                        'lastname' => $lastname,
                        'sex' => $sex,
                    ]);

                    $student = Student::create([
                        'student_number' => $studentnumber,
                        'program' => $program,
                        'year' => $year,
                        'personal_information' => $info->id
                    ]);

                    DB::commit();
                }


            }
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully imported',
            ], 200);
        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
            ], 500);
        }
    }

    public function export()
    {
        $fileName = 'backup.csv';
        $students = Student::with('info')->with('info.Address')->get();
        $questions = Question::with('choices')->get();

        $header = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(
            'lrn',
            'student_number',
            'email',
            'program',
            'year',
            'section',
            'firstname',
            'middlename',
            'lastname',
            'birthdate',
            'sex',
            'gender',
            'civilstatus',
            'cellphone',
            'weight',
            'height',
            'region',
            'province',
            'municipality',
            'barangay',
            'created_at',
            'surveys',
            'father_name',
            'father_occupation',
            'father_education',
            'father_contact_number',
            'mother_name',
            'mother_occupation',
            'mother_education',
            'mother_contact_number'
        );

        $callback = function () use($students, $columns, $questions){
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($students as $student){
                $region = null;
                $province = null;
                $municity = null;
                $barangay = null;

                if(!is_null($student->info->Address)) {
                    $address = $student->info->Address;

                    $region = Region::select('adm1_en')->where('adm1_pcode', $address->region)->first();
                    $province = Province::select('adm2_en')->where('adm2_pcode', $address->province)->first();
                    $municity = Municipality::select('adm3_en')->where('adm3_pcode', $address->municipality)->first();
                    $barangay = Barangay::select('adm4_en')->where('adm4_pcode', $address->barangay)->first();
                }

                $row['lrn'] = $student->lrn;
                $row['student_number'] = $student->student_number;
                $row['email'] = $student->email;
                $row['program'] = config('enums.programs.'.$student->program);
                $row['year'] = config('enums.years.'.$student->year);
                $row['section'] = $student->section;
                $row['firstname'] = $student->info->firstname;
                $row['middlename'] = $student->info->middlename;
                $row['lastname'] = $student->info->lastname;
                $row['birthdate'] = $student->info->birthdate? date_format($student->info->birthdate, 'd-m-y') : null;
                $row['sex'] = config('enums.sex.'.$student->info->sex);
                $row['gender'] = config('enums.gender.'.$student->info->gender);
                $row['civilstatus'] = config('enums.civilstatus.'.$student->info->civilstatus);
                $row['cellphone'] = $student->info->cellphone;
                $row['weight'] = $student->info->weight;
                $row['height'] = $student->info->height;
                $row['region'] = $region? $region->adm1_en : null;
                $row['province'] = $province? $province->adm2_en : null;
                $row['municipality'] = $municity? $municity->adm3_en : null;
                $row['barangay'] = $barangay? $barangay->adm4_en : null;
                $row['created_at'] = strtotime($student->created_at);

                if($student->answers->count() > 0) {
                    $anwsersToSave = [];
                    foreach ($student->answers as $answer) {
                        $answerToSave = array(
                            'question' => $answer->question->question,
                            'answer' => $answer->answer,
                            'created_at' => strtotime($answer->created_at),
                        );
                        $anwsersToSave[] = json_encode($answerToSave);
                    }

                    $row['surveys'] = implode('-',$anwsersToSave);
                }else{
                    $row['surveys'] = null;
                }

                if ($student->household)
                {
                    $row['father_name'] = $student->household->father_name?? '';
                    $row['father_occupation'] = config('enums.occupation.'.$student->household->father_occupation?? '');
                    $row['father_education'] = config('enums.educational_attainment.'.$student->household->father_education?? '');
                    $row['father_contact_number'] = $student->household->father_contact_number?? '';
                    $row['mother_name'] = $student->household->mother_name?? '';
                    $row['mother_occupation'] = config('enums.occupation.'.$student->household->mother_occupation?? '');
                    $row['mother_education'] = config('enums.educational_attainment.'.$student->household->mother_education?? '');
                    $row['mother_contact_number'] = $student->household->mother_contact_number?? '';
                }else {
                    $row['father_name'] = '';
                    $row['father_occupation'] = '';
                    $row['father_education'] = '';
                    $row['father_contact_number'] = '';
                    $row['mother_name'] = '';
                    $row['mother_occupation'] = '';
                    $row['mother_education'] = '';
                    $row['mother_contact_number'] = '';
                }


                $dataToSave = array(
                    $row['lrn'],
                    $row['student_number'],
                    $row['email'],
                    $row['program'],
                    $row['year'],
                    $row['section'],
                    $row['firstname'],
                    $row['middlename'],
                    $row['lastname'],
                    $row['birthdate'],
                    $row['sex'],
                    $row['gender'],
                    $row['civilstatus'],
                    $row['cellphone'],
                    $row['weight'],
                    $row['height'],
                    $row['region'],
                    $row['province'],
                    $row['municipality'],
                    $row['barangay'],
                    $row['created_at'],
                    $row['surveys'],
                    $row['father_name'],
                    $row['father_occupation'],
                    $row['father_education'],
                    $row['father_contact_number'],
                    $row['mother_name'],
                    $row['mother_occupation'],
                    $row['mother_education'],
                    $row['mother_contact_number'],
                );

                fputcsv($file, $dataToSave);

            }
            fclose($file);
        };

        return response()->stream($callback, 200, $header);
    }
}
