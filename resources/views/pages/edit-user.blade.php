@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>User</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="/users" aria-label="questions">Users</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{$user->id}}
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <x-input-component title="Name" id="name-input" placeholder="Enter Name" isRequired="true"/>
                        <x-input-component title="Email" id="email-input" placeholder="Enter Email" isRequired="true"/>
                        @role('Developer')
                        <x-select-component title="Role" id="role-input" placeholder="Enter Role" isRequired="true" :options="$roles" />
                        @else
                            <x-input-component title="Role" id="role-input" placeholder="Enter Role" isRequired="true" value="Teacher" />
                            @endrole
                        <button id="save-changes-btn" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
            <div id="choices" class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <button data-toggle="modal" data-target="#addChoices" class="btn btn-primary">Add Choice</button>
                        @foreach($question->choices as $choice)
                            <x-choice-input-component title="Choice" name="choice-input" id="choice-input-{{$choice->id}}" :questionId="$choice->question_id" :choiceId="$choice->id" placeholder="Enter Choice" isRequired="true" value="{{$choice->choice}}"></x-choice-input-component>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(function (){

            function save(){
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                $.ajax({
                    url: '/api/user/{{$user->id}}',
                    type: 'PUT',
                    data: {
                        name: $('#name-input').val(),
                        email: $('#type-input').val()
                    },
                    success: function (data) {
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
                                location.reload();
                            }
                        })
                    },
                    error: function (data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                })
            }
        })
    </script>
@stop
@section('plugins.Sweetalert2', true)
