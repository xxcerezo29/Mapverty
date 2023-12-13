@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('auth_body')
    <p class="login-box-msg">Enter your Student Number to start answering.</p>
    <form>
        @csrf
        <div id="student_number" class="input-group mb-3">
            <input type="text" id="student_number-input" name="student_number" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Student Number" autofocus>

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
            <span class="fas fa-magnifying-glass"></span>
            Find
        </button>
        <a href="{{route('survey.survey-new')}}" id="submit_BTN" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-plus"></span>
            New Student
        </a>
        <a href="/survey/change-email" id="submit_BTN" class="btn btn-block btn-flat">
            Change Email Address
        </a>
    </form>
@endsection
@pushonce('js')
    <script>
        $(function(){

            $('#student_number-input').inputmask('9{2}-9{1,6}')

            $('#submit_BTN').on('click', function (event) {
                event.preventDefault();
                var token = $('meta[name="csrf-token"]').attr('content');
                var data = {};
                data['student_number'] = $('#student_number-input').val();
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
                        url: '/api/survey/',
                        type: 'GET',
                        data: data,
                        success: function (data) {
                            console.log(data);
                            Swal.close();
                            Swal.fire({
                                icon: data.status,
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                if(data.hasSurvey === false){
                                    if(data.hasEmail === false){
                                        window.location.href = '/survey/email?student_number='+data.student.student_number;
                                    }else {
                                        window.location.href = '/survey/otp?student_number='+data.student.student_number;
                                    }
                                }
                            });
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
                    })
                );
            });
        });
    </script>
@endpushonce
@section('plugins.Sweetalert2', true)
@section('plugins.inputmask', true)
