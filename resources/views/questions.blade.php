@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Questions</h1>
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
    <x-datatable-component id="question" :data="$data_display" :columns="$columns" addBtnText="Add Question" url="/api/questions" addUrl="addQuestion" removeUrl="/api/questions/">
    </x-datatable-component>

    <x-modal-component id="addQuestion" datatable="question" title="Add Question" isForm="true" action="/api/questions" method="post" submitBtnText=""  >
        <x-input-component title="Question" id="question-input" placeholder="Enter Question" isRequired="true"/>
        <x-select-component title="Type" id="type-input" placeholder="Enter Type" isRequired="true" :options="$options" />
    </x-modal-component>
@stop

@section('css')
@stop

@section('js')
@stop
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesPlugins', true)
