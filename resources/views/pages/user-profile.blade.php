@extends('adminlte::page')
@section('title', 'User Profile')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>User's Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">

                        </div>
                        <h3 class="profile-username text-center">{{auth()->user()->name}}</h3>
                        <p class="text-muted text-center">
                            @role('Super Admin')
                                Super Admin
                            @elseif('Developer')
                                Developer
                            @else
                                Teacher
                                @endrole

                        </p>
                        <ul class="list-group list-group-unbordered m3">
                            <li class="list-group-item">
                                <b>Email</b>
                                <a class="float-right">{{auth()->user()->email}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Info</a></li>
                            <li class="nav-item"><a class="nav-link" href="#passwordchange" data-toggle="tab">Password Change</a></li>

                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="info">
                                <form id="infochange-form">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Full Name</label>
                                        <div class="col-sm-10">
                                            <input id="name" value="{{auth()->user()->name}}" name="name" type="text" class="form-control" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                                        <div class="col-sm-10">
                                            <input id="email" value="{{auth()->user()->email}}" name="email" type="text" class="form-control" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button id="submit-info-change" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="tab-pane" id="passwordchange">
                                <form id="passwordchange-form">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="current-password" class="col-sm-2 col-form-label">Current Password</label>
                                        <div class="col-sm-10">
                                            <input id="current-password" name="current_password" type="password" class="form-control" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="new-password" class="col-sm-2 col-form-label">New Password</label>
                                        <div class="col-sm-10">
                                            <input id="new-password" name="password" type="password" class="form-control" placeholder="New password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="new-password-confirm" class="col-sm-2 col-form-label">Confirm New Password</label>
                                        <div class="col-sm-10">
                                            <input id="new-password-confirm" name="password_confirmation" type="password" class="form-control" placeholder="Confirm new password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button id="submit-password-change" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('plugins.Sweetalert2', true)
@pushonce('js')
    <script>
        $(function (){
            $('#submit-password-change').on('click', function (e){
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to change your password.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, change it!'
                }).then((result)=>{
                    if(result.isConfirmed){
                        $.ajax({
                            headers: {

                            },
                            url: '/profile/password-change/',
                            type: 'PUT',
                            data: $('#passwordchange-form').serialize(),
                            success: function (data){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.title,
                                    text: data.message,
                                    allowOutsideClick: false, // Disallow clicking outside the alert to close
                                    allowEscapeKey: false, // Disallow closing the alert with the escape key
                                    showCancelButton: false, // Hide the cancel button
                                    confirmButtonText: 'OK'
                                }).then((result) =>{
                                    if(result.isConfirmed){
                                        location.reload();
                                        Swal.close();
                                    }
                                })
                            },
                            error: function (data){
                                Swal.fire({
                                    icon: 'error',
                                    title: data.responseJSON.title,
                                    text: data.responseJSON.message,
                                })
                            }

                        })
                    }
                })
            })

            $('#submit-info-change').on('click', function (e){
                e.preventDefault()
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to update your info.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result)=> {
                    if(result.isConfirmed){
                        $.ajax({
                            headers: {

                            },
                            url: '/profile/info-change',
                            type: 'PUT',
                            data: $('#infochange-form').serialize(),
                            success: function (data){
                                Swal.fire({
                                    icon: 'success',
                                    title: data.title,
                                    text: data.message,
                                    allowOutsideClick: false, // Disallow clicking outside the alert to close
                                    allowEscapeKey: false, // Disallow closing the alert with the escape key
                                    showCancelButton: false, // Hide the cancel button
                                    confirmButtonText: 'OK'
                                }).then((result) =>{
                                    if(result.isConfirmed){
                                        location.reload();
                                        Swal.close();
                                    }
                                })
                            },
                            error: function (data){
                                Swal.fire({
                                    icon: 'error',
                                    title: data.responseJSON.title,
                                    text: data.responseJSON.message,
                                })
                            }

                        })
                    }
                })
            })

        })


    </script>
@endpushonce
