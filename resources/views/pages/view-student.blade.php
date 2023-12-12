@extends('adminlte::page')
@section('title', 'Student')

@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Viewing Student Details</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="/students" aria-label="questions">Students</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{$student->student_number}}
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        Student Information
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Name: </p>
                                <p>Student Number: </p>
                                <p>LRN: </p>
                                <p>Program: </p>
                                <p>Year: </p>
                                <p>Section: </p>
                                <p>Address: </p>
                                <p>Birthdate: </p>
                                <p>Sex: </p>
                                <p>Gender: </p>
                                <p>Height: </p>
                                <p>Weight: </p>
                                <p>Civil Status: </p>
                                <p>Nationality: </p>
                                <p>BMI: </p>
                                <p>Email: </p>
                                <p>Contact Number: </p>
                                <hr>
                                <p>Household Information</p>
                                <hr>
                                <p>Father's Name:</p>
                                <p>Occupation</p>
                                <p>Highest Education:</p>
                                <p>Contact Number:</p>
                                <p>Mother's Name:</p>
                                <p>Occupation:</p>
                                <p>Highest Education:</p>
                                <p>Contact Number:</p>
                            </div>
                            <div class="col-md-6">
                                <p>{{$student->getFullNameAttribute()}}</p>
                                <p>{{$student->student_number}}</p>
                                <p>{{$student->lrn?? '---'}}</p>
                                <p>{{config('enums.programs.'.$student->program) ?? '---'}}</p>
                                <p>{{config('enums.years.'.$student->year)?? '---'}}</p>
                                <p>{{ $student->section?? '---'}}</p>
                                <p>{{ $student->address_string()?? '---' }}</p>
                                <p>{{$student->info->birthdate? date_format($student->info->birthdate, 'd/m/y') : '---'}}</p>
                                <p>{{ config('enums.sex.'.$student->info->sex) ?? '---'}}</p>
                                <p>{{ config('enums.gender.'.$student->info->gender)?? '---'}}</p>
                                <p>{{$student->info->height}} cm</p>
                                <p>{{$student->info->weight}} kg</p>
                                <p>{{ config('enums.civilstatus.'.$student->info->civilstatus)?? '---'}}</p>
                                <p>{{ config('nationality.'.$student->info->nationality)?? '---' }}</p>
                                <p>{{$student->BMI() !== 0? round($student->BMI(), 2) : '---'}}</p>
                                <p>{{$student->email?? '---'}}</p>
                                <p>{{$student->info->cellphone?? '---'}}</p>
                                <hr>
                                <p>----------------------</p>
                                <hr>
                                <p>{{$student->household->father_name ?? '---'}}</p>
                                <p>{{ $student->household? config('enums.occupation.'.$student->household->father_occupation?? '') : '---'}}</p>
                                <p>{{ $student->household? config('enums.educational_attainment.'.$student->household->father_education?? '') : '---'  }}</p>
                                <p>{{ $student->household->father_contact_number ?? '---' }}</p>
                                <p>{{$student->household->mother_name ?? '---'}}</p>
                                <p>{{ $student->household? config('enums.occupation.'.$student->household->mother_occupation?? '') : '---'}}</p>
                                <p>{{ $student->household? config('enums.educational_attainment.'.$student->household->mother_education?? '') : '---'  }}</p>
                                <p>{{ $student->household->mother_contact_number ?? '---' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        Survey Results
                        <hr>
                        @if($student->answers->count() > 0)
                            @foreach($student->answers as $answer)
                                <p class="text-info">{{$answer->question->question}}</p>
                                <p class="text-danger">{{$answer->answer}}</p>
                                <hr>
                            @endforeach
                        @else
                            <p class="text-danger">No survey results yet</p>
                        @endif

                    </div>
                </div>
        </div>
    </div>
@stop
