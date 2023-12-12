@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
            <x-small-box-component id="small-box-1" color="info" icon="fa fa-users" title="Total Students" text="{{$studentsCount}}" />
            <x-small-box-component id="small-box-2" color="success" icon="fa fa-users" title="Total Respondent" text="{{$respondent}}" />
            <x-small-box-component id="small-box-3" color="warning" icon="fa fa-signal" title="Total Poverty Index" text="{{$povertyindex}}%" />
            <x-small-box-component id="small-box-4" color="danger" icon="fa fa-users" title="Total First Generation Student" text="{{$firstGeneration}}" />
    </div>

    <div class="row">
        <div class="col-md-8">
        <x-line-chart-component ajaxURL="/api/poverty/line" id="povertyStatusYear" title="Poverty Status" description="100" />
        <x-bar-chart-component ajaxURL="/api/poverty/indices" id="indices" title="Poverty Indices" description="100" />
        </div>
        <div class="col-md-4">
            <x-doughnut-chart-component  ajaxURL="/api/poverty/doughnut" id="poverty" title="Poverty Status" />
            <x-doughnut-chart-component ajaxURL="/api/first-generation/chart" id="first_generation" title="First Generation Student" />
            <x-doughnut-chart-component  ajaxURL="/api/malnutrition" id="malnutrition" title="Malnutrition Status" />
        </div>
    </div>

@stop

@section('css')
@stop

@section('js')
    <script src="//cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="/js/MapVerty.js"></script>
@stop
@section('plugins.Chartjs', true)
