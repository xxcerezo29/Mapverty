@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        Users
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop
@section('content')
    <x-datatable-component id="user" :data="$data_display" :columns="$columns" addBtnText="Add User" url="/api/users" addUrl="addUser" removeUrl="/api/users/">
    </x-datatable-component>
    <x-modal-component id="addUser" datatable="user" title="Add User" isForm="true" action="/api/users?role" method="post" submitBtnText=""  >
        <x-input-component title="Name" id="name-input" placeholder="Enter Name" isRequired="true"/>
        <x-input-component title="Email" id="email-input" placeholder="Enter Email" isRequired="true"/>
        @role('Developer')
            <x-select-component title="Role" id="role-input" placeholder="Enter Role" isRequired="true" :options="$roles" />
        @else
            <x-input-component title="Role" id="role-input" placeholder="Enter Role" isRequired="true" value="Teacher" />
            @endrole
{{--        <x-select-component title="Type" id="type-input" placeholder="Enter Type" isRequired="true" :options="$options" />--}}
    </x-modal-component>
    <x-modal-component id="ChangeUserEmail" datatable="user" title="Edit User" isForm="true" action="/api/users/update-email" method="post" submitBtnText="changeEmail">

        <x-input-component isHidden="true" title="User Id" id="userid-input" placeholder="User Id" isRequired="true"/>
        <x-input-component title="Email" id="email-update-input" placeholder="Enter Email" isRequired="true"/>
    </x-modal-component>
@stop
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesPlugins', true)

@pushonce('js')
    <script>
        $('#ChangeUserEmail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var userid = button.data('id') // Extract info from data-* attributes
            $('#userid-input').val(userid);
        })
    </script>
@endpushonce
