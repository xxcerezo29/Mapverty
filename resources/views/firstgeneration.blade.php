@extends('adminlte::page')
@section('title', 'First Generation Student')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>First Generation Student</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        First Generation Student
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleSelectBorder">Sex Category</label>
                            <select id="sex_selector" name="sex_selector" class="custom-select form-control-border" id="exampleSelectBorder">
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <x-datatable-component id="firstgeneration" excelUrl="/api/first-generation/export"  :data="$data_display" :columns="$columns" url="/api/first-generation?sex=1"></x-datatable-component>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <x-doughnut-chart-component ajaxURL="/api/first-generation/chart" id="first_generation" title="First Generation Student" />
        </div>
    </div>
@stop

@push('js')
    <script src="//cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="/js/MapVerty.js"></script>

    <script>
        $('#sex_selector').on('change', function (){
            data = $('#sex_selector').val()
            $('#firstgeneration').DataTable().ajax.url('/api/first-generation?sex='+data).load();
        })
    </script>
@endpush

@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesPlugins', true)

