@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('auth_body')
    <p class="login-box-msg">Verifying Student</p>
    <form id="form">
        @csrf
        <div id="student_number" class="input-group mb-3" hidden>
            <input type="email" id="student_number-main-input" name="email" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Student Number" value="{{$student->student_number}}" >

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <div id="student_number" class="input-group mb-3">
            <input type="email" id="email-main-input" name="email" class="form-control  "
                    placeholder="Email">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
                    <strong>Invalid Email</strong>
            </span>
        </div>
        <div id="student_firstname" class="input-group mb-3">
            <input type="text" id="student_firstname-main-input" name="student_firstname" class="form-control @error('name') is-invalid @enderror"
                   placeholder="First Name">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <div id="student_middlename" class="input-group mb-3">
            <input type="text" id="student_middlename-main-input" name="student_middlename" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Middlename">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <div id="student_lastname" class="input-group mb-3">
            <input type="text" id="student_lastname-main-input" name="student_lastname" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Lastname" >

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <button type="submit" id="submit_BTN" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-pencil"></span>
            Submit
        </button>
        <a href="/survey/" id="submit_BTN" class="btn btn-block btn-flat">
            Cancel
        </a>
    </form>

    <x-modal-component :reloadWhenSubmit="true" static="true" submitBtnText="" id="verifyEmail" title="Verify Email Address" isForm="true" action="/api/survey/verify-email-code" method="post">
        <div class="row">
            <div class="col-md-12">
                <x-input-component title="Verification Code" type="text" id="verify_code-input" placeholder="Enter Verification Code" isRequired="true"/>
            </div>
            <x-input-component  title="Verification Code" isHidden="true" type="text" id="id-input" placeholder="Enter Verification Code" isRequired="true"/>
            <x-input-component title="Verification Code" isHidden="true" type="text" id="student_number-input" placeholder="Enter Verification Code" isRequired="true"/>
            <x-input-component title="Verification Code" isHidden="true" type="text" id="email-input" placeholder="Enter Verification Code" isRequired="true"/>
            <x-input-component title="Verification Code" isHidden="true" type="text" id="student_firstname-input" placeholder="Enter Verification Code" isRequired="true"/>
            <x-input-component title="Verification Code" isHidden="true" type="text" id="student_lastname-input" placeholder="Enter Verification Code" isRequired="true"/>
            <x-input-component title="Verification Code" isHidden="true" type="text" id="student_middlename-input" placeholder="Enter Verification Code" isRequired="true"/>

        </div>
    </x-modal-component>

@endsection
@pushonce('js')
    <script>
        $(function(){
            const form = document.getElementById('form');
            const inputs = form.getElementsByTagName('input');

            submitBTN = $('#submit_BTN');
            submitBTN.prop('disabled', true);
            emailInput = $('#email-main-input');
            firstnameInput = $('#student_firstname-main-input');
            middlenameInput = $('#student_middlename-main-input');
            lastnameInput = $('#student_lastname-main-input');

            emailInput.on('change', function (){
                validateEmail();
            })

            for (let i = 0; i < inputs.length; i++) {
                inputs[i].addEventListener('input', checkInputs);
            }

            function validateEmail() {
                const emailInput = document.getElementById('email-input').value;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if(emailPattern.test(emailInput)) {
                    document.getElementById('email-input').classList.remove('is-invalid');
                    document.getElementById('email-input').classList.add('is-valid');
                    submitBTN.prop('disabled', false);
                } else {
                    document.getElementById('email-input').classList.remove('is-valid');
                    document.getElementById('email-input').classList.add('is-invalid');
                    $('#verify-email').hide();
                    submitBTN.prop('disabled', true);
                    disableBTN();
                }
            }

            function disableBTN(){
                submitBTN.prop('disabled', true);
            }

            submitBTN.on('click', function (){
                var token = $('meta[name="csrf-token"]').attr('content');
                var data = {};
                data['email'] = $('#email-main-input').val();
                data['student_number'] = $('#student_number-main-input').val();
                data['id'] = {{$student->id}};
                data['student_firstname'] = $('#student_firstname-main-input').val();
                data['student_middlename'] = $('#student_middlename-main-input').val();
                data['student_lastname'] = $('#student_lastname-main-input').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                $.ajax({
                    url: '/api/survey/verify-email',
                    type: 'POST',
                    data: data,
                    success: function (data) {
                        console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                            $('#id-input').val({{$student->id}}),
                            $('#student_number-input').val($('#student_number-main-input').val()),
                            $('#email-input').val(emailInput.val()),
                            $('#student_firstname-input').val(firstnameInput.val()),
                            $('#student_middlename-input').val(middlenameInput.val()),
                            $('#student_lastname-input').val(lastnameInput.val()),
                            $('#verifyEmail').modal('show')
                        );


                    },
                    error: function (data) {
                        console.log(data);
                        Swal.fire({
                            icon: 'error',
                            title: data.title,
                            text: data.responseJSON.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            })

            function checkInputs(){
                let allFilled = true;

                for (let i = 0; i < inputs.length; i++) {
                    if(inputs[i].value === ''){
                        allFilled = false;
                        break;
                    }
                }

                submitBTN.prop('disabled', !allFilled);
            }

            {{--$('#submit_BTN').on('click', function (event) {--}}
            {{--    event.preventDefault();--}}
            {{--    var token = $('meta[name="csrf-token"]').attr('content');--}}
            {{--    var data = {};--}}
            {{--    data['id'] = {{$student->id}};--}}
            {{--    data['student_number'] = $('#student_number-input').val();--}}
            {{--    data['email'] = $('#email-input').val();--}}
            {{--    data['student_firstname'] = $('#student_firstname-input').val();--}}
            {{--    data['student_middlename'] = $('#student_middlename-input').val();--}}
            {{--    data['student_lastname'] = $('#student_lastname-input').val();--}}
            {{--    $.ajaxSetup({--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': token--}}
            {{--        }--}}
            {{--    });--}}
            {{--    $.ajax({--}}
            {{--        url: '/api/survey/verify',--}}
            {{--        type: 'POST',--}}
            {{--        data: data,--}}
            {{--        success: function (data) {--}}
            {{--            console.log(data);--}}

            {{--            Swal.fire({--}}
            {{--                icon: 'success',--}}
            {{--                title: data.title,--}}
            {{--                text: data.message,--}}
            {{--                confirmButtonText: 'Ok',--}}
            {{--                timer: 1500--}}
            {{--            }).then((result) => {--}}
            {{--                if (result.isConfirmed) {--}}
            {{--                    window.location.href = '/survey/otp?student_number='+data.student.student_number;--}}
            {{--                }--}}
            {{--            })--}}
            {{--        },--}}
            {{--        error: function (data) {--}}
            {{--            console.log(data);--}}
            {{--            Swal.fire({--}}
            {{--                icon: 'error',--}}
            {{--                title: data.title,--}}
            {{--                text: data.responseJSON.message,--}}
            {{--                showConfirmButton: false,--}}
            {{--                timer: 1500--}}
            {{--            });--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
        });
    </script>
@endpushonce
@section('plugins.Sweetalert2', true)
