@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@php( $password_email_url = View::getSection('password_email_url') ?? config('adminlte.password_email_url', 'password/email') )

@if (config('adminlte.use_route_url', false))
    @php( $password_email_url = $password_email_url ? route($password_email_url) : '' )
@else
    @php( $password_email_url = $password_email_url ? url($password_email_url) : '' )
@endif

@section('auth_header', __('adminlte::adminlte.password_reset_message'))

@section('auth_body')

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form id="password_reset" action="{{ $password_email_url }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Send reset link button --}}
        <button id="submit" type="button" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-share-square"></span>
            {{ __('adminlte::adminlte.send_password_reset_link') }}
        </button>

    </form>

@stop
@section('plugins.Sweetalert2', true)
@pushonce('js')
    <script>
        $('#submit').on('click', function (){
            const data = $('#password_reset').serialize();

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
                    url: '{{ $password_email_url }}',
                    type: 'POST',
                    data: data,
                    success: function (data){
                        console.log(data);
                        Swal.close();
                        if(data.title === 'Successful')
                        {
                            Swal.fire({
                                icon: 'success',
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                            })
                        }else {
                            Swal.fire({
                                icon: 'error',
                                title: data.title,
                                text: data.message,
                                showConfirmButton: false,
                            })
                        }

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
            )
        })
    </script>
@endpushonce
