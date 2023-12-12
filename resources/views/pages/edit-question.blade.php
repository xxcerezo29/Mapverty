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
                    <li class="breadcrumb-item">
                        <a href="/questions" aria-label="questions">Questions</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{$question->id}}
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <x-input-component title="Question" id="question-input" placeholder="Enter Question" isRequired="true" value="{{$question->question}}"></x-input-component>
                        <x-select-component title="Type" id="type-input" placeholder="Enter Type" isRequired="true" :options="$options" value="{{$question->type}}"></x-select-component>
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

    <x-modal-component reloadWhenSubmit="true" id="addChoices" title="Add Choice" isForm="true" action="/api/questions/{{$question->id}}/choices" method="post" submitBtnText="" >
        <x-input-component title="Choice Description" id="choice-input" placeholder="Choice" isRequired="true"/>
    </x-modal-component>
@stop

@section('css')
@stop

@section('js')
    <script>
        $(function (){
            var question = {!! json_encode($question) !!};

            $('#addChoices').on('show.bs.modal', function (e) {
                if(question.type != 2) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Please click save before adding choices!',
                    })
                }
            })

            if(question.type == 2) {
                $('#choices').show();
                if (question.choices.length <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'This Question need choices, Please add choices!',
                    })
                }
            }else {
                $('#choices').hide();
            }

            $('#type-input').on('change', function (){
                if($(this).val() == 2) {
                    $('#choices').show();
                } else {
                    $('#choices').hide();
                }
            });

            $('#save-changes-btn').on('click', function (){
                if (question.type == 2 && question.choices.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to save this changes? Choices will be deleted too!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            save();
                        }
                    })
                }else{
                    save();
                }



            })

            function save(){
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                Swal.fire({
                    title: 'Please Wait',
                    text: 'We are saving your changes',
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
                        url: '/api/questions/'+question.id,
                        type: 'PUT',
                        data: {
                            question: $('#question-input').val(),
                            type: $('#type-input').val()
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
                )
            }
        })
    </script>
@stop
@section('plugins.Sweetalert2', true)
