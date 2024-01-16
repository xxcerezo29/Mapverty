@extends('adminlte::page')
@section('title', 'Student')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Editing Student Details</h1>
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
    <div class="p-2">
        <h1>Student Information</h1>
        <div class="row">
            <div class="col-md-4">
                <x-select-component :value="$student->program" id="program-input" :options="$programs" title="Program" isRequired="true" ></x-select-component>
            </div>
            <div class="col-md-4">
                <x-input-component :value="$student->section" title="Sections" type="number" id="section-input" placeholder="Enter Section" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-select-component :value="$student->year" id="year-input" :options="$years" title="Year" isRequired="true" ></x-select-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <x-input-component value="{{ $student->lrn }}" title="LRN" id="lrn-input" placeholder="Enter LRN" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-input-component value="{{ $student->student_number }}" title="Student Number" id="student_number-input" placeholder="Enter Student Number" isRequired="true"/>
            </div>
        </div>
        <h2>Personal Information</h2>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <x-input-component value="{{ $student->info->firstname }}" title="First Name" id="first_name-input" placeholder="Enter First Name" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-input-component value="{{ $student->info->middlename }}" title="Middle Name" id="middle_name-input" placeholder="Enter Middle Name" isRequired="false"/>
            </div>
            <div class="col-md-4">
                <x-input-component value="{{ $student->info->lastname }}" title="Last Name" id="last_name-input" placeholder="Enter Last Name" isRequired="true"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <x-input-component :value="(new DateTIme($student->info->birthdate))->format('Y-m-d')" title="Birthdate" id="birthdate-input" placeholder="Enter Birthdate" type="date" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-select-component :value="$student->info->sex" id="sex-input" :options="$sex" title="Sex" isRequired="true" ></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component :value="$student->info->gender" id="gender-input" :options="$gender" title="Gender" isRequired="true"></x-select-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <x-input-component :value="$student->info->weight" id="weight-input" title="Weight"  customValidation="(kg)" placeholder="Enter Weight" isRequired="true"></x-input-component>
            </div>
            <div class="col-md-6">
                <x-input-component :value="$student->info->height" id="height-input" title="Height" customValidation="(cm)"  placeholder="Enter Height" isRequired="true"></x-input-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <x-input-component :value="$student->info->cellphone" id="phone-input" title="Phone Number"  placeholder="Enter Phone Number" isRequired="true"></x-input-component>
            </div>
            <div class="col-md-6">
                <x-input-component :value="$student->email" id="email-input" title="Email" customValidation="(valid and active Email)" placeholder="Enter Email" isRequired="true"></x-input-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <x-select-component :value="$student->info->civilstatus" id="civil_status-input" :options="$civilStatus" title="Civil Status" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-6">
                <x-select-component :value="$student->info->nationality" id="nationality-input" :options="$nationality" title="Nationality" isRequired="false"></x-select-component>
            </div>
        </div>
        <hr>
        <h4>Address</h4>
        <div class="row">
            <div class="col-md-12">
                <x-select-component value="{{$student->info->Address->region?? ''}}"  id="region-input" title="Region" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component value="{{$student->info->Address->province?? ''}}" id="province-input" title="Province" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component value="{{$student->info->Address->municipality?? ''}}" id="city-input" title="Municipality/City" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component value="{{$student->info->Address->barangay?? ''}}" id="barangay-input" title="Barangay" isRequired="true"></x-select-component>
            </div>
        </div>
        <hr>
        <div class="row justify-content-between">
            <button type="button" onclick="logout()" class="btn btn-default">Cancel</button>
            <button type="button"  id="submit-main_btn" class="btn btn-success">Submit</button>

        </div>
    </div>
@stop
@section('plugins.Sweetalert2', true)
@section('plugins.inputmask', true)
@section('plugins.Select2', true)
@section('js')
    <script>
        $(function ()
        {
            var token = $('meta[name="csrf-token"]').attr('content');

            $('#section-input').attr('min',1).attr('value',1).attr('max',5);
            $('#lrn-input').inputmask("999999-99-9999");
            $('#phone-input').inputmask("(+63) 9999999999");
            $('#email-input').inputmask('email');
            $('#weight-input').inputmask('9{2,3}');
            $('#height-input').inputmask('9{3}');
            $('#student_number-input').inputmask('9{2}-9{1,6}');

            fetchRegion();
            @if($student->info->address && $student->info->Address->region) fetchProvince('{{$student->info->Address->region}}'); @endif
            @if($student->info->address && $student->info->Address->province) fetchCity('{{$student->info->Address->province}}'); @endif
            @if($student->info->address && $student->info->Address->municipality) fetchBarangay('{{$student->info->Address->municipality}}'); @endif

            $('#region-input').on('change', function (e){
                fetchProvince(e.target.value);
                $('#province-input').html('');
                $('#city-input').html('');
                $('#barangay-input').html('');
            });
            $('#province-input').on('change', function (e){
                fetchCity(e.target.value);
                $('#city-input').html('');
                $('#barangay-input').html('');
            });
            $('#city-input').on('change', function (e){
                fetchBarangay(e.target.value);
                $('#barangay-input').html('');
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            $('#submit-main_btn').on('click', function (){
                var data = {};
                $('.x-input-component').each(function (index, element){
                    if(element.id.startsWith('question')){
                        const id = element.id.split('-')[1];
                        surveyValues[id] = element.value;
                    }
                    else{
                        data[element.id.replace('-input','')] = element.value;
                    }
                });
                data['student'] = '{{ $student->id }}';
                Swal.fire({
                    title: 'Please Wait',
                    text: 'Saving Changes',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    onOpen: () => {
                        Swal.showLoading();
                    }
                }).then(
                    $.ajax({
                        url: '/api/students/{{$student->id}}',
                        type: 'put',
                        data: data,
                        success: function (data){
                            console.log(data);
                            Swal.fire({
                                title: 'Success',
                                text: 'Changes Saved',
                                icon: 'success',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                timer: 2000,
                                onClose: () => {
                                    window.location.href = '/students/'+data.id;
                                }
                            });
                        },
                        error: function (data){
                            errors = data.responseJSON.errors;
                            for (error in errors){
                                i = 0;
                                if (errors.hasOwnProperty(error)) {
                                    if(error.includes('survey'))
                                    {
                                        i+= 1;
                                        $('#question-'+i+'-input').addClass('is-invalid');
                                    }else{
                                        $('#' + error+'-input').addClass('is-invalid');
                                    }
                                }
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.responseJSON.message?? data.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
                )
            })
        }
        )
        function fetchRegion(){
            $.ajax({
                url: '/api/address/regions',
                type: 'get',
                data: {
                    '_token': '{{csrf_token()}}',
                    'forSelect' : true
                },
                success: function (data){
                    var options = '';
                    data.forEach(function (region){
                        @if($student->info->address && $student->info->Address->region)
                        if(region.adm1_pcode === '{{$student->info->Address->region}}')
                            options += '<option value="'+region.adm1_pcode+'" selected>'+region.adm1_en+'</option>';
                        else
                            options += '<option value="'+region.adm1_pcode+'" >'+region.adm1_en+'</option>';
                        @else
                            options += '<option value="'+region.adm1_pcode+'" >'+region.adm1_en+'</option>';
                        @endif
                            options += '<option value="'+region.adm1_pcode+'">'+region.adm1_en+'</option>';
                    });
                    $('#region-input').html(options);
                }
            });
        }
        function fetchProvince(val){
            $.ajax({
                url: '/api/address/provinces/'+val,
                type: 'get',
                data: {
                    '_token': '{{csrf_token()}}',
                },
                success: function (data){
                    var options = '';
                    data.forEach(function (province){
                        @if($student->info->address && $student->info->Address->province)
                        if (province.adm2_pcode === '{{$student->info->Address->province}}')
                            options += '<option value="'+province.adm2_pcode+'" selected>'+province.adm2_en+'</option>';
                        else
                            options += '<option value="'+province.adm2_pcode+'">'+province.adm2_en+'</option>';
                        @else
                            options += '<option value="'+province.adm2_pcode+'">'+province.adm2_en+'</option>';
                        @endif
                    });
                    $('#province-input').html(options);
                }
            })
        }
        function fetchCity(val){
            $.ajax({
                url: '/api/address/municipalities/'+val,
                type: 'get',
                data: {
                    '_token': '{{csrf_token()}}',
                },
                success: function (data){
                    var options = '';
                    data.forEach(function (municipalities){
                        @if($student->info->address && $student->info->Address->municipality)
                        if(municipalities.adm3_pcode === '{{$student->info->Address->municipality}}')
                            options += '<option value="'+municipalities.adm3_pcode+'" selected>'+municipalities.adm3_en+'</option>';
                        else
                            options += '<option value="'+municipalities.adm3_pcode+'">'+municipalities.adm3_en+'</option>';
                        @else
                            options += '<option value="'+municipalities.adm3_pcode+'">'+municipalities.adm3_en+'</option>';
                        @endif                        });
                    $('#city-input').html(options);
                }
            })
        }
        function fetchBarangay(val){
            $.ajax({
                url: '/api/address/barangays/'+val,
                type: 'get',
                data: {
                    '_token': '{{csrf_token()}}',
                },
                success: function (data){
                    var options = '';
                    data.forEach(function (barangay){
                        @if($student->info->address && $student->info->Address->barangay)
                        if (barangay.adm4_pcode === '{{$student->info->Address->barangay}}')
                            options += '<option value="'+barangay.adm4_pcode+'" selected>'+barangay.adm4_en+'</option>';
                        else
                            options += '<option value="'+barangay.adm4_pcode+'">'+barangay.adm4_en+'</option>';
                        @else
                            options += '<option value="'+barangay.adm4_pcode+'">'+barangay.adm4_en+'</option>';
                        @endif                        });
                    $('#barangay-input').html(options);
                }
            })
        }
    </script>
@stop
