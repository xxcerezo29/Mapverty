@extends('adminlte::page')
@section('title', 'Student')

@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Import or Export Data</h1>
            </div>
        </div>
    </div>
@stop
@pushonce('css')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endpushonce
@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Import Student Information</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="year">Select Sources <span class="text-danger">*</span></label>
                        <select id="source" name="source"
                                class="custom-select form-control-border @error('source') is-invalid @enderror">
                            <option value="">Select Sources</option>
                            <option value="1">MapVerty Backup Source</option>
                            <option value="2">Import Source</option>
                        </select>
                    </div>
                    <form id="file-upload-form" action="" method="POST" enctype="multipart/form-data" class="dropzone">
                        @csrf
                    </form>
                    <button id="submit-button" class="btn btn-primary">Submit</button>

                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Export Student Information</h3>
                    </div>
                </div>
                <div class="card-body">
                    <button id="export" class="btn btn-outline-info">Export</button>
                </div>
            </div>
        </div>
    </div>
@stop

@pushonce('js')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        $(document).ready(function (){
            var myDropzone = new Dropzone('#file-upload-form', {
                url: '/import-export/import', // Set the URL to handle file uploads
                paramName: 'file', // The name of the file parameter
                maxFilesize: 5, // Maximum file size in megabytes
                acceptedFiles: '.csv', // Allowed file extensions
                dictDefaultMessage: 'Drop your CSV file here or click to upload.',
                dictFallbackMessage: 'Your browser does not support drag and drop file uploads.',
                {{--dictFileTooBig: 'File is too big ({{filesize}}MiB). Max file size: {{maxFilesize}}MiB.',--}}
                dictInvalidFileType: 'Invalid file type. Only CSV files are allowed.',
                dictResponseError: 'Server error. Please try again later.',
                addRemoveLinks: true,
                autoProcessQueue: false,
                parallelUploads: 10,
            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#submit-button').click(function (e) {
                e.preventDefault(); // Prevent the default form submission
                if(myDropzone.getQueuedFiles().length === 0 || $('#source').val() === ''){
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please select a file to upload or select a source',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                    return false;
                }

                var formData = new FormData();
                formData.append('file', myDropzone.getQueuedFiles()[0]);
                formData.append('source', $('#source').val());

                Swal.fire({
                    icon: 'info',
                    title: 'Pleasse wait...',
                    text: 'Importing Data... Do not close this window',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }).then(
                    $.ajax({
                        url: '/api/import-export/import',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response){
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: response.title,
                                text: response.message,
                                allowOutsideClick: false, // Disallow clicking outside the alert to close
                                allowEscapeKey: false, // Disallow closing the alert with the escape key
                                showCancelButton: false, // Hide the cancel button
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    })
                )
            });

        })

        $('#export').on('click', function (){
            Swal.fire({
                title: 'Exporting',
                text: 'Please wait...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            }).then(
                $.ajax({
                    url: '/api/import-export/export',
                    method: 'GET',
                    success: function (response){
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: response.title,
                            text: response.message,
                            allowOutsideClick: false, // Disallow clicking outside the alert to close
                            allowEscapeKey: false, // Disallow closing the alert with the escape key
                            showCancelButton: false, // Hide the cancel button
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/api/import-export/export';
                            }
                        })
                    },
                    error: function (response){
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: response.title,
                            text: response.message,
                            allowOutsideClick: false, // Disallow clicking outside the alert to close
                            allowEscapeKey: false, // Disallow closing the alert with the escape key
                            showCancelButton: false, // Hide the cancel button
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                })
            )
        })
    </script>
@endpushonce
@section('plugins.Sweetalert2', true)
