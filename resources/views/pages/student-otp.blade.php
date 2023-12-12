@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('auth_body')
    <p class="login-box-msg">One time password was sent at your email address. Please do not share this One time password to others.</p>
    <form>
        @csrf
        <div id="student_number" class="input-group mb-3" hidden>
            <input type="email" id="student_number-input" name="email" class="form-control @error('name') is-invalid @enderror"
                   placeholder="Email" value="{{$student->student_number}}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <div id="student_firstname" class="input-group mb-3">
            <input type="text" id="otp-input" name="otp" class="form-control @error('name') is-invalid @enderror"
                   placeholder="OTP">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            <span class="invalid-feedback" role="alert">
{{--                    <strong>{{ $message }}</strong>--}}
            </span>
        </div>
        <button type="submit" id="submit_BTN" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-user-plus"></span>
            Save Changes
        </button>
    </form>
@endsection
@pushonce('js')
    <script>
        $(function(){
            $('#submit_BTN').on('click', function (event) {
                event.preventDefault();
                var token = $('meta[name="csrf-token"]').attr('content');
                var data = {};
                data['student_number'] = $('#student_number-input').val();
                data['otp'] = $('#otp-input').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                $.ajax({
                    url: '/api/survey/verify-otp',
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
                            window.location.href = '/survey/otp?student_number='+data.student.student_number;
                        })
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
            });
        });
    </script>
@endpushonce
@section('plugins.Sweetalert2', true)
