<div class="modal fade" id="{{$id}}" data-backdrop="{{ $static === true? 'static': '' }}" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if($isForm == 'true')
                <form id="{{$id}}-form">

                    <div class="modal-body">
                        {{$slot}}
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="{{$submitBtnText}}" class="btn btn-success">Submit</button>
                    </div>
                </form>
            @else
                <div class="modal-body">
                    {{$slot}}
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="{{$submitBtnText}}" class="btn btn-success">Submit</button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('js')
    <script>
        $(function(){
            var input;
            $('#{{$id}}').on('show.bs.modal', function (event) {
                input = $('#{{$id}}-form .x-input-component');
            });
            $('#{{$id}}').on('hidden.bs.modal', function (event) {
                input.each(function (index, element){
                    element.value = '';
                    element.classList.remove('is-invalid');
                });
            })

            @if($isForm === 'true')
                $('#{{$submitBtnText}}').on('click',function (){
                var data = {};
                var token = $('meta[name="csrf-token"]').attr('content');

                $('.x-input-component').each(function (index, element){
                    data[element.id.replace('-input','')] = element.value;
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    allowOutsideClick: false, // Disallow clicking outside the alert to close
                    allowEscapeKey: false, // Disallow closing the alert with the escape key
                    showCancelButton: true, // Show the cancel button
                    confirmButtonText: 'Yes, submit it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.close();
                        Swal.fire({
                            title: 'Please Wait',
                            text: 'Processing Data',
                            allowOutsideClick: false, // Disallow clicking outside the alert to close
                            allowEscapeKey: false, // Disallow closing the alert with the escape key
                            showCancelButton: false, // Hide the cancel button
                            showConfirmButton: false, // Show the confirm button
                            onOpen: () => {
                                Swal.showLoading();
                            }
                        }).then(
                            $.ajax({
                                url: '{{$action}}',
                                type: '{{$method}}',
                                data: data,
                                success: function (data) {
                                    console.log(data);

                                    $('#{{$id}}').modal('hide');


                                    @if($datatable)
                                    $('#{{$datatable}}').DataTable().ajax.reload();
                                    @endif

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
                                            @if($reloadWhenSubmit == true)
                                            location.reload();
                                            @endif

                                            Swal.close();

                                        }
                                    })


                                },
                                error: function (data) {
                                    errors = data.responseJSON.errors;

                                    console.log(data);

                                    for (error in errors){
                                        if (errors.hasOwnProperty(error)) {
                                            $('#' + error+'-input').addClass('is-invalid');
                                        }
                                    }

                                    Swal.fire({
                                        icon: 'error',
                                        title: data.statusText,
                                        text: data.responseJSON.message,
                                    })
                                }
                            })
                        )
                    }
                })
            })
            @endif


        })
    </script>
@endpush
