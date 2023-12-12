@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('auth_body')
    <p class="login-box-msg">Change Email</p>
    <form id="form">
        @csrf
        <div id="student_number" class="input-group mb-3" >
            <input type="text" id="student_number-main-input" name="email" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Student Number" value="" >

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
            <input type="text" id="email-main-input" name="email" class="form-control  "
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
            <input type="text" id="lrn-main-input" name="lrn" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Enter your LRN">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-id-card {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <button type="submit" id="submit_main_BTN" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-floppy-disk"></span>
            Save Changes
        </button>
        <a href="/survey/" id="submit_BTN" class="btn btn-block btn-flat">
            Cancel
        </a>
    </form>

    <x-modal-component :reloadWhenSubmit="true" static="true" submitBtnText="" id="verifyEmail" title="Verify Email Address" isForm="false" action="" method="">
        <div class="row">
            <div class="col-md-12">
                <x-input-component title="Verification Code" type="text" id="verify_code-input" placeholder="Enter Verification Code" isRequired="true"/>
            </div>
        </div>
    </x-modal-component>

@endsection
@pushonce('js')
    <script>
        $(function(){
            $('#lrn-main-input').inputmask("999999-99-9999");
            $('#student_number-main-input').inputmask('9{2}-9{1,6}');
            $('#email-main-input').inputmask('email');

            const form = document.getElementById('form');
            const inputs = form.getElementsByTagName('input');

            submitBTN = $('#submit_main_BTN');
            submitBTN.prop('disabled', true);
            modalSubmitBTN = $('#submit_btn');
            const emailInput = $('#email-main-input');

            emailInput.on('change', function (){
                validateEmail();
            })

            for (let i = 0; i < inputs.length; i++) {
                inputs[i].addEventListener('input', checkInputs);
            }

            function validateEmail() {
                const emailInput = document.getElementById('email-main-input').value;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if(emailPattern.test(emailInput)) {
                    document.getElementById('email-main-input').classList.remove('is-invalid');
                    document.getElementById('email-main-input').classList.add('is-valid');
                    submitBTN.prop('disabled', false);
                } else {
                    document.getElementById('email-main-input').classList.remove('is-valid');
                    document.getElementById('email-main-input').classList.add('is-invalid');
                    $('#verify-email').hide();
                    submitBTN.prop('disabled', true);
                    disableBTN();
                }
            }

            function disableBTN(){
                submitBTN.prop('disabled', true);
            }

            modalSubmitBTN.on('click', function (e){
                e.preventDefault();
                var token = $('meta[name="csrf-token"]').attr('content');
                var data = {};
                data['email'] = $('#email-main-input').val();
                data['student_number'] = $('#student_number-main-input').val();
                data['lrn'] = $('#lrn-main-input').val();
                data['code'] = $('#verify_code-input').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                $.ajax({
                    url: '/api/survey/verifyChangeEmail',
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
                        }).then((result) => {
                                if (data.status === 'success') {
                                    location.reload();
                                }
                            }
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

            submitBTN.on('click', function (e){
                e.preventDefault();
                var token = $('meta[name="csrf-token"]').attr('content');
                var data = {};
                data['email'] = $('#email-main-input').val();
                data['student_number'] = $('#student_number-main-input').val();
                data['lrn'] = $('#lrn-main-input').val();
                {{--data['id'] = {{$student->id}};--}}
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                $.ajax({
                    url: '/api/survey/verifyChangeEmail',
                    type: 'GET',
                    data: data,
                    success: function (data) {
                        console.log(data);
                        Swal.fire({
                            icon: 'success',
                            title: data.title,
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then((result) => {
                                if (data.status === 'success') {
                                    $('#verifyEmail').modal('show')
                                }
                            }
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
        });
    </script>
@endpushonce
@section('plugins.Sweetalert2', true)
@section('plugins.inputmask', true)
