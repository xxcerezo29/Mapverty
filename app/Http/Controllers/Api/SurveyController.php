<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentNew;
use App\Http\Requests\UpdateStudent;
use App\Models\Answer;
use App\Models\HouseholdInformation;
use App\Models\PersonalInformation;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function getStudent(Request $request){
        $validated = $request->validate([
            'student_number' => 'required'
        ]);

        try {
            $student = \App\Models\Student::where('student_number', $validated['student_number'])->firstOrFail();

            if($student->email == null && $student->lrn == null){
                return response()->json([
                    'title' => 'Student Found',
                    'status' => 'success',
                    'message' => 'Student found',
                    'hasEmail' => $student->email != null,
                    'hasLRN' => $student->lrn != null,
                    'student' => $student,
                    'hasSurvey' => false,
                ]);
            }else if ($student->email != null && $student->lrn == null) {

                $otp = new Otp();
                $code = $otp->generate($student->student_number, 6, 10);

                return response()->json([
                    'title' => 'Student Found',
                    'status' => 'success',
                    'message' => 'Student found',
                    'hasEmail' => $student->email != null,
                    'hasLRN' => $student->lrn != null,
                    'student' => $student,
                    'hasSurvey' => false,
                ]);
            }

            if($student->answers->count() > 0){
                return response()->json([
                    'title' => 'Student Already Answered',
                    'status' => 'info',
                    'message' => 'Student already answered',
                    'hasEmail' => $student->email != null,
                    'hasLRN' => $student->lrn != null,
                    'student' => $student,
                    'hasSurvey' => true,
                ]);
            }

            $otp = new Otp();
            $code = $otp->generate($student->student_number, 6, 10);

            // emailing

            return response()->json([
                'title' => 'Student Found',
                'status' => 'success',
                'message' => 'Student found',
                'hasEmail' => $student->email,
//                'hasSurvey' => $student->survey != null,
                'hasLRN' => $student->lrn != null,
                'student' => $student,
                'hasSurvey' => false,
            ]);


        } catch (\Throwable $th) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    public function store(StoreStudentNew $request){
        try {
            DB::beginTransaction();

            $address = \App\Models\Address::create([
                'region' => $request->region,
                'province' => $request->province,
                'municipality' => $request->city,
                'barangay' => $request->barangay,
            ]);

            $info = PersonalInformation::create([
                'firstname' => strtoupper($request->first_name),
                'middlename' => $request->middle_name? strtoupper($request->middle_name) : '',
                'lastname' => strtoupper($request->last_name),
                'birthdate' => $request->birthdate,
                'sex' => $request->sex,
                'gender' => $request->gender,
                'weight' => $request->weight,
                'height' => $request->height,
                'civilstatus' => $request->civil_status,
                'address' => $address->id,
                'cellphone' => $request->phone,
                'nationality' => $request->nationality,
            ]);



            $student = \App\Models\Student::create([
                'lrn' => $request->lrn,
                'student_number' => $request->student_number,
                'email' => $request->email,
                'program' => $request->program,
                'year' => $request->year,
                'section' => $request->section,
                'personal_information' => $info->id,
            ]);

            foreach ($request->survey as $key => $value) {
                Answer::create([
                    'student_id' => $student->id,
                    'question_id' => $key,
                    'answer' => $value,
                ]);
            }

            $household = HouseholdInformation::create(
                [
                    'student' => $student->id,
                    'father_name' => strtoupper($request->father_firstname . ' '. $request->father_middlename . ' ' . $request->father_lastname),
                    'father_occupation' => $request->father_occupation,
                    'father_education' => $request->father_education,
                    'mother_name' => strtoupper($request->mother_firstname . ' '. $request->mother_middlename . ' ' . $request->mother_lastname),
                    'mother_occupation' => $request->mother_occupation,
                    'mother_education' => $request->mother_education,
                ]
            );

            DB::commit();

            return response()->json([
                'title' => 'Added',
                'status' => 'success',
                'message' => 'Student added',
                'student' => $student,
            ]);

        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(UpdateStudent $request){
        try {
            DB::beginTransaction();

            $student = \App\Models\Student::where('student_number', $request->student_number)->firstOrFail();
            $info = $student->info;
            $address = $info->Address?? null;

            if($request->isEmailVerified == false){
                return response()->json([
                    'title' => 'Error',
                    'status' => 'error',
                    'message' => 'Email not verified',
                ], 404);
            }else{
                if($address == null) {
                    $address = \App\Models\Address::create([
                        'region' => $request->region,
                        'province' => $request->province,
                        'municipality' => $request->city,
                        'barangay' => $request->barangay,
                    ]);

                    $info->address = $address->id;

                }else {
                    $address->region = $request->region;
                    $address->province = $request->province;
                    $address->municipality = $request->city;
                    $address->barangay = $request->barangay;
                    $address->save();
                }

                $info->firstname = strtoupper($request->first_name);
                $info->middlename = $request->middle_name ? strtoupper($request->middle_name) : '';
                $info->lastname = strtoupper($request->last_name);
                $info->birthdate = $request->birthdate;
                $info->sex = $request->sex;
                $info->gender = $request->gender;
                $info->weight = $request->weight;
                $info->height = $request->height;
                $info->civilstatus = $request->civil_status;
                $info->nationality = $request->nationality;
                $info->cellphone = $request->phone;
                $info->save();

                $student->lrn = $request->lrn;
                $student->student_number = $request->student_number;
                $student->email = $request->email;
                $student->program = $request->program;
                $student->year = $request->year;
                $student->section = $request->section;
                $student->save();

                foreach ($request->survey as $key => $value) {
                    Answer::create([
                        'student_id' => $student->id,
                        'question_id' => $key,
                        'answer' => $value,
                    ]);
                }

                if($student->household == null) {
                    $household = HouseholdInformation::create(
                        [
                            'student' => $student->id,
                            'father_name' => strtoupper($request->father_firstname . ' ' . $request->father_middlename . ' ' . $request->father_lastname),
                            'father_occupation' => $request->father_occupation,
                            'father_education' => $request->father_education,
                            'father_contact_number' => $request->father_contact,
                            'mother_name' => strtoupper($request->mother_firstname . ' ' . $request->mother_middlename . ' ' . $request->mother_lastname),
                            'mother_occupation' => $request->mother_occupation,
                            'mother_education' => $request->mother_education,
                            'mother_contact_number' => $request->mother_contact,
                        ]
                    );
                }else{
                    $household = $student->household;
                    $household->father_name = strtoupper($request->father_firstname . ' ' . $request->father_middlename . ' ' . $request->father_lastname);
                    $household->father_occupation = $request->father_occupation;
                    $household->father_education = $request->father_education;
                    $household->mother_name = strtoupper($request->mother_firstname . ' ' . $request->mother_middlename . ' ' . $request->mother_lastname);
                    $household->mother_occupation = $request->mother_occupation;
                    $household->mother_education = $request->mother_education;
                    $household->save();
                }

                DB::commit();

                Auth::guard('students')->logout();

                return response()->json([
                    'title' => 'Updated',
                    'status' => 'success',
                    'message' => 'Student updated',
                    'student' => $student,
                ]);
            }


        }catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        }
    }


    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required',
            'email' => 'required|email|unique:students,email,' . ($request->has('id') ? $request->id : ''),
            'student_firstname' => 'required',
            'student_lastname' => 'required',
            'student_middlename' => 'required',
        ]);

        try {

            $student = \App\Models\Student::where('student_number', $validated['student_number'])->firstOrFail();
            $info = $student->info;

            if ($info->lastname == strtoupper($validated['student_lastname']) && $student->student_number == $validated['student_number']) {
                $otp = new Otp();

                $generated = $otp->generate($validated['student_number'], 6, 10);

                return response()->json([
                    'title' => 'OTP Sent',
                    'status' => 'success',
                    'codeStatus' => true,
                    'message' => 'Verification Code was sent to your email',
                    'student' => $student,
                    'info' => $info,
                ]);
            }else {
                Throw new \Exception('Info not match');
            }

        }catch (\Exception $e) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function EmailVerify(Request $request){
        $validated = $request->validate([
            'lrn'=> 'required|unique:students,'.($request->has('id')? 'lrn,'.$request->id: 'lrn'),
            'student_number' => 'required|unique:students,'. ($request->has('id') ? 'student_number,'.$request->id : 'student_number'),
            'email' => 'required|email|unique:students,'.($request->has('id') ? 'email,'.$request->id : 'email'),
        ]);

        try {
            $otp = new Otp();
            $generated = $otp->generate($validated['student_number'], 6, 10);

            return response()->json([
                'title' => 'OTP Sent',
                'status' => 'success',
                'codeStatus' => true,
                'message' => 'Verification Code was sent to your email',
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'codeStatus' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
    public  function  CodeEmailVerify(Request $request){
        $validated = $request->validate([
            'student_number' => 'required',
            'code' => 'required',
        ]);

        try {
            $otp = new Otp();

            $verified = $otp->validate($validated['student_number'], $validated['code']);

            if($verified->status === true){
                return response()->json([
                    'title' => 'Email Verified',
                    'status' => 'success',
                    'codeStatus' => true,
                    'message' => 'Verified',
                ]);
            }else {
                throw new \Exception($verified->message);
            }
        }catch (\Exception $e) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function verifyEmailCode(Request $request){
        $validated = $request->validate([
            'verify_code' => 'required',
            'student_number' => 'required',
            'email' => 'required|email|unique:students,email,'.($request->has('id')? $request->id : ''),
            'student_firstname' => 'required',
            'student_lastname' => 'required',
            'student_middlename' => 'required',
        ]);

        try {
            $otp = new Otp();

            $verified = $otp->validate($validated['student_number'], $validated['verify_code']);

            if($verified->status === true){
                DB::beginTransaction();
                $student = \App\Models\Student::where('student_number', $validated['student_number'])->firstOrFail();
                $info = $student->info;

                if ($info->lastname == strtoupper($validated['student_lastname']) && $student->student_number == $validated['student_number']) {
                    $student->email = $validated['email'];
                    $student->save();
                    $student->email_verified_at = now();
                    DB::commit();

                } else {
                    DB::rollback();
                    return response()->json([
                        'title' => 'Error',
                        'status' => 'error',
                        'message' => 'Info not match',
                    ], 404);
                }

                Auth::guard('students')->login($student);

                if(!$student->markEmailAsVerified()){
                    event(new \Illuminate\Auth\Events\Verified($student));
                }

                return response()->json([
                    'title' => 'Added',
                    'status' => 'success',
                    'message' => 'Email added',
                    'student' => $student,
                ]);
            }

            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $verified->message,
            ], 404);

        }catch (\Throwable $th) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => 'Email code not match',
            ], 404);
        }
    }

    public function verifyOTP(Request $request){
        $validated = $request->validate([
            'otp' => 'required',
            'student_number' => 'required',
        ]);

        try {
            $otp = new Otp();
            $verified = $otp->validate($validated['student_number'], $validated['otp']);

            if($verified->status === true){
                $student = \App\Models\Student::where('student_number', $validated['student_number'])->firstOrFail();
                Auth::guard('students')->login($student);

                return response()->json([
                    'title' => 'Verified',
                    'status' => 'success',
                    'message' => 'Verified Student',
                    'student' => $student,
                ]);
            }else {
                return response()->json([
                    'title' => 'Error',
                    'status' => 'error',
                    'message' => $verified->message,
                ], 404);
            }
        }catch (\Exception $e) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function changeEmail(Request $request){
        try {
            $validated = $request->validate([
                'student_number' => 'required',
                'lrn' => 'required',
            ]);

            $student = \App\Models\Student::where('student_number', $validated['student_number'])->where('lrn', $validated['lrn'])->first();

            if($student == null){
                throw new \Exception('Credentials not match');
            }

            $emailValidated = $request->validate([
                'email' => 'required|email|unique:students,email,'.$student->id,
            ]);

            if($student->email == $emailValidated['email']){
                throw new \Exception('You already have this email');
            }

            $otp = new Otp();
            $generated = $otp->generate($validated['student_number'], 6, 10);

            return response()->json([
                'title' => 'OTP Sent',
                'status' => 'success',
                'message' => 'Verification Code was sent to your email',
                'student' => $student,
            ]);

        }catch (\Exception $e) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function storeChangeEmail(Request $request){
        try {
            $validated = $request->validate([
                'student_number' => 'required',
                'lrn' => 'required',
                'email' => 'required',
                'code' => 'required',
            ]);
            $student = \App\Models\Student::where('student_number', $validated['student_number'])->where('lrn', $validated['lrn'])->first();

            if($student == null){
                throw new \Exception('Credentials not match');
            }

            $otp = new Otp();
            $verified = $otp->validate($validated['student_number'], $validated['code']);

            if($verified->status === true){
                $student->email = $validated['email'];
                $student->email_verified_at = now();
                $student->save();

                Auth::guard('students')->login($student);

                return response()->json([
                    'title' => 'Changed',
                    'status' => 'success',
                    'message' => 'Email Changed',
                    'student' => $student,
                ]);
            }else{
                throw new \Exception($verified->message);
            }

        }catch (\Exception $e) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function logout(Request $request){
        try {
            Auth::guard('students')->logout();
            return response()->json([
                'title' => 'Logout',
                'status' => 'success',
                'message' => 'Logout successfully',
            ]);
        }catch (\Throwable $th) {
            return response()->json([
                'title' => 'Error',
                'status' => 'error',
                'message' => 'Logout failed',
            ], 404);
        }

    }
}
