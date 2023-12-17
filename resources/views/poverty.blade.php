@extends('adminlte::page')
@section('title', 'Poverty Status')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Poverty Status</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        Poverty
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
                            <label for="exampleSelectBorder">Poverty Category</label>
                            <select id="poverty_selector" name="bmi_selector" class="custom-select form-control-border" id="exampleSelectBorder">
                                <option value="0">All</option>
                                <option value="Below">Below Poverty Line</option>
                                <option value="Above">Above Poverty Line</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <x-datatable-component id="poverty" excelUrl="/api/poverty/export"  :data="$data_display" :columns="$columns" url="/api/poverty"></x-datatable-component>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <x-doughnut-chart-component ajaxURL="/api/poverty/doughnut" id="poverty" title="Poverty Status" />
        </div>
    </div>
@stop

@push('js')
    <script src="//cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="/js/MapVerty.js"></script>

    <script>
        $('#poverty_selector').on('change', function (){
            data = $('#poverty_selector').val()
            $('#poverty').DataTable().ajax.url('/api/poverty?poverty_status='+data).load();
        })
    </script>
@endpush

@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesPlugins', true)

