@extends('adminlte::page')
@section('title', 'Students')

@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Students</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        Students
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <x-datatable-component id="student" excelUrl="/api/students/export" :data="$data_display" :columns="$columns" addBtnText="Add Student" url="/api/students" addUrl="addStudent" removeUrl="/api/students/"></x-datatable-component>

    <x-modal-component id="addStudent" datatable="student" title="Add Student" isForm="true" action="/api/students" method="post" submitBtnText=""  >
        <h2>Student Information</h2>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <x-select-component id="program-input" :options="$programs" title="Program" isRequired="true" ></x-select-component>
            </div>
            <div class="col-md-4">
                <x-input-component title="Sections" type="number" id="section-input" placeholder="Enter Section" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-select-component id="year-input" :options="$years" title="Year" isRequired="true" ></x-select-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <x-input-component title="LRN" id="lrn-input" placeholder="Enter LRN" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-input-component title="Student Number" id="student_number-input" placeholder="Enter Student Number" isRequired="true"/>
            </div>
        </div>
        <hr>
        <h2>Personal Information</h2>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <x-input-component title="First Name" id="first_name-input" placeholder="Enter First Name" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-input-component title="Middle Name" id="middle_name-input" placeholder="Enter Middle Name" isRequired="false"/>
            </div>
            <div class="col-md-4">
                <x-input-component title="Last Name" id="last_name-input" placeholder="Enter Last Name" isRequired="true"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <x-input-component title="Birthdate" id="birthdate-input" placeholder="Enter Birthdate" type="date" isRequired="true"/>
            </div>
            <div class="col-md-4">
                <x-select-component id="sex-input" :options="$sex" title="Sex" isRequired="true" ></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component id="gender-input" :options="$gender" title="Gender" isRequired="true"></x-select-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <x-input-component id="weight-input" title="Weight"  customValidation="(kg)" placeholder="Enter Weight" isRequired="true"></x-input-component>
            </div>
            <div class="col-md-6">
                <x-input-component id="height-input" title="Height" customValidation="(cm)"  placeholder="Enter Height" isRequired="true"></x-input-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <x-input-component id="phone-input" title="Phone Number"  placeholder="Enter Phone Number" isRequired="true"></x-input-component>
            </div>
            <div class="col-md-6">
                <x-input-component id="email-input" title="Email" customValidation="(valid and active Email)" placeholder="Enter Email" isRequired="true"></x-input-component>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <x-select-component id="civil_status-input" :options="$civilStatus" title="Civil Status" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-6">
                <x-select-component id="nationality-input" :options="$nationality" title="Nationality" isRequired="false"></x-select-component>
            </div>
        </div>
        <hr>
        <h2>Address</h2>
        <div class="row">
            <div class="col-md-12">
                <x-select-component id="region-input" title="Region" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component id="province-input" title="Province" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component id="city-input" title="Municipality/City" isRequired="true"></x-select-component>
            </div>
            <div class="col-md-4">
                <x-select-component id="barangay-input" title="Barangay" isRequired="true"></x-select-component>
            </div>
        </div>
    </x-modal-component>
@stop

@section('css')
@stop

@pushonce('js')
    <script>
        $(function (){
            $('#section-input').attr('min',1).attr('value',1).attr('max',5);
            $('#lrn-input').inputmask("999999-99-9999");
            $('#phone-input').inputmask("(+63) 9999999999");
            $('#email-input').inputmask('email');
            $('#weight-input').inputmask('9{2,3}');
            $('#height-input').inputmask('9{3}');
            $('#nationality-input').css('width', '100%').removeClass('form-control-border form-control').addClass('select2').select2();
            $('#student_number-input').inputmask('9{2}-9{1,6}');

            fetchRegion();

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
                            options += '<option value="'+province.adm2_pcode+'">'+province.adm2_en+'</option>';
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
                            options += '<option value="'+municipalities.adm3_pcode+'">'+municipalities.adm3_en+'</option>';
                        });
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
                            options += '<option value="'+barangay.adm4_pcode+'">'+barangay.adm4_en+'</option>';
                        });
                        $('#barangay-input').html(options);
                    }
                })
            }

            function remove(student_nubmer){
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to delete this item.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) =>{
                    if (result.isConfirmed) {
                        var token = $('meta[name="csrf-token"]').attr('content');

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': token
                            }
                        });

                        $.ajax({
                            url: '/api/students/'+student_nubmer,
                            type: 'DELETE',
                            success: function (data) {
                                console.log(data);

                                Swal.fire({
                                    icon: 'success',
                                    title: data.title,
                                    text: data.message,
                                })

                                $('#student').DataTable().ajax.reload();
                            },
                            error: function (data) {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.statusText,
                                    text: data.responseJSON.message,
                                })
                            }
                        });
                    }
                })
            }
        })
    </script>
@endpushonce
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.inputmask', true)
@section('plugins.Select2', true)
