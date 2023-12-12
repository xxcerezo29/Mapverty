@extends('adminlte::auth.survey-page', ['auth_type' => 'login'])
@section('auth_body')
    <h1>Student Information</h1>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <x-select-component :value="auth()->user()->program" id="program-input" :options="$programs" title="Program" isRequired="true" ></x-select-component>
        </div>
        <div class="col-md-4">
            <x-input-component :value="auth()->user()->section" title="Sections" type="number" id="section-input" placeholder="Enter Section" isRequired="true"/>
        </div>
        <div class="col-md-4">
            <x-select-component :value="auth()->user()->year" id="year-input" :options="$years" title="Year" isRequired="true" ></x-select-component>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <x-input-component :value="auth()->user()->lrn" title="LRN" id="lrn-input" placeholder="Enter LRN" isRequired="true"/>
        </div>
        <div class="col-md-4">
            <x-input-component :value="auth()->user()->student_number" title="Student Number" id="student_number-input" placeholder="Enter Student Number" isRequired="true"/>
        </div>
    </div>
    <hr>
    <h2>Personal Information</h2>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <x-input-component :value="auth()->user()->info->firstname" title="First Name" id="first_name-input" placeholder="Enter First Name" isRequired="true"/>
        </div>
        <div class="col-md-4">
            <x-input-component :value="auth()->user()->info->middlename" title="Middle Name" id="middle_name-input" placeholder="Enter Middle Name" isRequired="false"/>
        </div>
        <div class="col-md-4">
            <x-input-component :value="auth()->user()->info->lastname" title="Last Name" id="last_name-input" placeholder="Enter Last Name" isRequired="true"/>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <x-input-component :value="(new DateTime(auth()->user()->info->birthdate))->format('Y-m-d')" title="Birthdate" id="birthdate-input" placeholder="Enter Birthdate" type="date" isRequired="true"/>
        </div>
        <div class="col-md-4">
            <x-select-component :value="auth()->user()->info->sex" id="sex-input" :options="$sex" title="Sex" isRequired="true" ></x-select-component>
        </div>
        <div class="col-md-4">
            <x-select-component :value="auth()->user()->info->gender" id="gender-input" :options="$gender" title="Gender" isRequired="true"></x-select-component>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <x-input-component :value="auth()->user()->info->weight" id="weight-input" title="Weight"  customValidation="(kg)" placeholder="Enter Weight" isRequired="true"></x-input-component>
        </div>
        <div class="col-md-6">
            <x-input-component :value="auth()->user()->info->height" id="height-input" title="Height" customValidation="(cm)"  placeholder="Enter Height" isRequired="true"></x-input-component>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <x-input-component :value="auth()->user()->info->cellphone" id="phone-input" title="Phone Number"  placeholder="Enter Phone Number" isRequired="true"></x-input-component>
        </div>
        <div class="col-md-6">
            <x-input-component :value="auth()->user()->email" id="email-input" title="Email" customValidation="(valid and active Email)" placeholder="Enter Email" isRequired="true"></x-input-component>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <x-select-component :value="auth()->user()->info->civilstatus" id="civil_status-input" :options="$civilStatus" title="Civil Status" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-6">
            <x-select-component :value="auth()->user()->info->nationality" id="nationality-input" :options="$nationality" title="Nationality" isRequired="false"></x-select-component>
        </div>
    </div>
    <hr>
    <h4>Address</h4>
    <div class="row">
        <div class="col-md-12">
            <x-select-component value="{{auth()->user()->info->Address->region?? ''}}"  id="region-input" title="Region" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-select-component value="{{auth()->user()->info->Address->province?? ''}}" id="province-input" title="Province" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-select-component value="{{auth()->user()->info->Address->municipality?? ''}}" id="city-input" title="Municipality/City" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-select-component value="{{auth()->user()->info->Address->barangay?? ''}}" id="barangay-input" title="Barangay" isRequired="true"></x-select-component>
        </div>
    </div>
    <hr>
    <h4>Parent/Guardian Information</h4>
    <hr>
    <h4>Father Information</h4>
    <div class="row">
        <div class="col-md-4">
            <x-input-component  id="father_firstname-input"  title="First Name" placeholder="First Name" isRequired="true" ></x-input-component>
        </div>
        <div class="col-md-4">
            <x-input-component  title="Middle Name" id="father_middlename-input" placeholder="Middle Name"  isRequired="false"/>
        </div>
        <div class="col-md-4">
            <x-input-component  id="father_lastname-input" title="Last Name" placeholder="Last Name" isRequired="true" ></x-input-component>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <x-select-component  id="father_occupation-input" :options="$occupation" title="Occupation" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-select-component  id="father_education-input" :options="$education" title="Education" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-input-component  id="father_contact-input" title="Contact Number" placeholder="Contact Number" isRequired="false" ></x-input-component>
        </div>
    </div>
    <h4>Mother Information</h4>
    <div class="row">
        <div class="col-md-4">
            <x-input-component  id="mother_firstname-input"  title="First Name" placeholder="First Name" isRequired="true" ></x-input-component>
        </div>
        <div class="col-md-4">
            <x-input-component  title="Middle Name" id="mother_middlename-input" placeholder="Middle Name"  isRequired="false"/>
        </div>
        <div class="col-md-4">
            <x-input-component  id="mother_lastname-input" title="Last Name" placeholder="Last Name" isRequired="true" ></x-input-component>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <x-select-component  id="mother_occupation-input" :options="$occupation" title="Occupation" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-select-component  id="mother_education-input" :options="$education" title="Education" isRequired="true"></x-select-component>
        </div>
        <div class="col-md-4">
            <x-input-component  id="mother_contact-input" title="Contact Number" placeholder="Contact Number" isRequired="false" ></x-input-component>
        </div>
    </div>
    <hr>
    <h1>Survey</h1>
    <hr>
    @foreach($questions as $question)
        <x-survey-question-component :choices="$question->choices" :question="$question"></x-survey-question-component>
    @endforeach

    <div class="row justify-content-between">
        <button type="button" onclick="logout()" class="btn btn-default">Cancel</button>
        <div class="row">
            <button type="button" onclick="verifyEmail($('#email-input'))" id="verify_btn" class="btn btn-info">Verify Email</button>
            <button type="button"  id="submit-main_btn" class="btn btn-success">Submit</button>
        </div>
    </div>


    <x-modal-component :reloadWhenSubmit="false" isForm="false" static="true"  submitBtnText="" id="verifyEmail" title="Verify Email Address" action="" method="">
        <div class="row">
            <div class="col-md-12">
                <x-input-component title="Verification Code" type="text" id="verify_code-input" placeholder="Enter Verification Code" isRequired="true"/>
            </div>
        </div>
    </x-modal-component>
@endsection

@section('plugins.Sweetalert2', true)
@section('plugins.inputmask', true)
@section('plugins.Select2', true)
@section('js')
    <script>
        function logout(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of the system!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/survey/logout',
                        type: 'POST',
                        data: {
                            '_token': '{{csrf_token()}}',
                        },
                        success: function (){
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'You have been logged out of the system!',
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) => {
                                window.location.href = '/survey';
                            })
                        },
                        error: function (data){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.responseJSON.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
                }
            })
        }
        function verifyEmail(email){
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            Swal.fire({
                title: 'Please Wait',
                text: 'We are checking your information',
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
                    url: '/api/survey/email',
                    type: 'POST',
                    data: {

                        'id' : '{{auth()->user()->id}}',
                        'lrn' : '{{auth()->user()->lrn}}',
                        'student_number' : '{{auth()->user()->student_number}}',
                        'email' : email.val(),
                    },
                    success: function (data){
                        Swal.fire({
                            icon: 'success',
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                            if(data.codeStatus === true){
                                $('#verifyEmail').modal('show');
                            }
                        })
                    },
                    error: function (data){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.responseJSON.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
            )
        }
        $(function (){
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            const email =  $('#email-input');
            const submitBtn = $('#submit-main_btn');
            const emailVerifyBtn = $('#verify_btn');
            const modalBTN = $('#submit_btn');

            var isEmailVerified = true;

            emailVerifyBtn.hide();
            $('#section-input').attr('min',1).attr('value',1).attr('max',5);
            $('#lrn-input').inputmask("999999-99-9999");
            $('#phone-input').inputmask("(+63) 9999999999");

            email.inputmask('email');
            $('#weight-input').inputmask('9{2,3}');
            $('#height-input').inputmask('9{3}');
            $('#nationality-input').css('width', '100%').removeClass('form-control-border form-control').addClass('select2').select2();
            $('#student_number-input').inputmask('9{2}-9{1,6}');
            fetchRegion();
            @if(auth('students')->user()->info->address && auth('students')->user()->info->Address->region) fetchProvince('{{auth('students')->user()->info->Address->region}}'); @endif
            @if(auth('students')->user()->info->address && auth('students')->user()->info->Address->province) fetchCity('{{auth('students')->user()->info->Address->province}}'); @endif
            @if(auth('students')->user()->info->address && auth('students')->user()->info->Address->municipality) fetchBarangay('{{auth('students')->user()->info->Address->municipality}}'); @endif



            email.on('change', function (e){
                if(e.target.value === '{{auth()->user()->email}}') {
                    emailVerifyBtn.hide();
                    isEmailVerified = true;
                }else {
                    submitBtn.prop('disabled', true);
                    isEmailVerified = false;
                    emailVerifyBtn.show();
                }
            })

            modalBTN.on('click', function (){
                var data = {};
                data['student_number'] = $('#student_number-input').val();
                data['code'] = $('#verify_code-input').val();
                data['email'] = $('#email-input').val();

                Swal.fire({
                    title: 'Please Wait',
                    text: 'We are checking your information',
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
                        url: '/api/survey/otp',
                        type: 'POST',
                        data: data,
                        success: function (data){
                            Swal.fire({
                                icon: 'success',
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then((result) =>{
                                    if(data.codeStatus === true){
                                        $('#verifyEmail').modal('hide');
                                        isEmailVerified = true;
                                        emailVerifyBtn.prop('disabled', true);
                                        emailVerifyBtn.html('Verified');
                                        submitBtn.prop('disabled', false);
                                    }
                                }
                            )
                        },
                        error: function (data){
                            console.log(data);
                            Swal.fire({
                                icon: 'error',
                                title: data.title,
                                text: data.responseJSON.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }

                    })
                )

            })

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

            submitBtn.on('click', function (){
                var data = {};
                var token = $('meta[name="csrf-token"]').attr('content');

                const surveyValues = {};

                $('.x-input-component').each(function (index, element){
                    if(element.id.startsWith('question')){
                        const id = element.id.split('-')[1];
                        surveyValues[id] = element.value;
                    }
                    else{
                        data[element.id.replace('-input','')] = element.value;
                    }
                });


                data['survey'] = surveyValues;
                data['isEmailVerified'] = isEmailVerified;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                Swal.fire({
                    title: 'Please Wait',
                    text: 'We are checking your information',
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
                        url: '/api/survey',
                        type: 'PUT',
                        data: data,
                        success: function (data) {
                            console.log(data);

                            Swal.fire({
                                icon: 'success',
                                title: data.title,
                                text: data.message,
                                allowOutsideClick: false, // Disallow clicking outside the alert to close
                                allowEscapeKey: false, // Disallow closing the alert with the escape key
                                showCancelButton: false, // Hide the cancel button
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/survey';
                                }
                            })
                        },
                        error: function (data) {
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
                        },
                    })
                )
            })


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
                            @if(auth()->user()->info->address && auth()->user()->info->Address->region)
                            if(region.adm1_pcode === '{{auth()->user()->info->Address->region}}')
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
                            @if(auth()->user()->info->address && auth()->user()->info->Address->province)
                            if (province.adm2_pcode === '{{auth()->user()->info->Address->province}}')
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
                            @if(auth('students')->user()->info->address && auth('students')->user()->info->Address->municipality)
                            if(municipalities.adm3_pcode === '{{auth('students')->user()->info->Address->municipality}}')
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
                            @if(auth('students')->user()->info->address && auth('students')->user()->info->Address->barangay)
                            if (barangay.adm4_pcode === '{{auth('students')->user()->info->Address->barangay}}')
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
        })
    </script>
@stop
