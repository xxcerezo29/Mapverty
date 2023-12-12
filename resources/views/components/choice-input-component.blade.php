<div class="form-group">
    <label for="{{$id}}">{{ $title }} {{ $isRequired? '*' : '' }}</label>
    <div class="input-group input-group-sm">
        <input type="text" name="{{$name}}" id="{{$id}}" class="form-control" value="{{$value}}">
        <span class="input-group-append">
            <button onclick="save()" type="button" class="btn btn-success btn-flat">Save</button>
            <button onclick="removeChoice()" type="button" class="btn btn-danger btn-flat">Remove</button>
        </span>
    </div>
</div>

@pushonce('js')
    <script>
        function save(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to save this choice?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var token = $('meta[name="csrf-token"]').attr('content');
                    var choice = $('#choice-input-'+{!! $choice_id !!}).val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });

                    $.ajax({
                        url: '/api/questions/'+{!! $question_id !!}+'/choices/'+{!! $choice_id !!},
                        type: 'PUT',
                        data: {
                            choice: choice
                        },
                        success: function (data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Choice Updated Successfully!',
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
                }
            })
        }
        function removeChoice(id, question_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var token = $('meta[name="csrf-token"]').attr('content');


                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });

                    $.ajax({
                        url: '/api/questions/'+{!! $question_id !!}+'/choices/'+{!! $choice_id !!},
                        type: 'DELETE',
                        success: function (data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Choice Deleted Successfully!',
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
                }
            })

        }
    </script>
@endpushonce
