@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Malnutrition Status</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        Question
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
                            <label for="exampleSelectBorder">BMI Category</label>
                            <select id="bmi_selector" name="bmi_selector" class="custom-select form-control-border" id="exampleSelectBorder">
                                <option value="0">All</option>
                                <option value="1">Very severely underweight</option>
                                <option value="2">Severely underweight</option>
                                <option value="3">Underweight</option>
                                <option value="4">Normal (healthy weight)</option>
                                <option value="5">Overweight</option>
                                <option value="6">Obese Class I (Moderately obese)</option>
                                <option value="7">Obese Class II (Severely obese)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <x-datatable-component id="malutrition" :param="$param" excelUrl="/api/malnutrition/export"  :data="$data_display" :columns="$columns" url="/api/malnutrition/datatable"></x-datatable-component>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <x-doughnut-chart-component id="malnutrition" ajaxURL="/api/malnutrition" title="Malnutrition Status" />
        </div>
    </div>
@stop

@push('js')
    <script src="//cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="/js/MapVerty.js"></script>

    <script>
        $('#bmi_selector').on('change', function (){
            data = $('#bmi_selector').val()
            $('#malutrition').DataTable().ajax.url('/api/malnutrition/datatable?bmi_selector='+data).load();
        })
    </script>
@endpush

@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesPlugins', true)

