<div class="table-responsive p-0">
    <table id="{{$id}}" class="table table-bordered table-hover text-nowrap">
        <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{$column}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
        <tr>
            @foreach ($columns as $column)
                <th>{{$column}}</th>
            @endforeach
        </tr>
        </tfoot>
    </table>
</div>

@section('js')
<script>
    $(function () {
        $('#{{$id}}').DataTable({
            processing: true,
            ajax: {
                url: '{{$url}}',
                type: 'GET',
                data: {

                },
                dataSrc: 'data'
            },
            columns: @json($data_display),
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                @if($addBtnText)
                {
                    'text': '{{ $addBtnText }}',
                    'className': 'btn btn-success',

                    attr: {
                        'data-toggle': 'modal',
                        'data-target': '#{{$addUrl}}',
                    }
                },
                @endif
                    @if($excelUrl)
                {
                    'text': 'Generate Excel File',
                    'className': 'btn btn-info',
                    attr : {
                        'id': '{{$id}}_excel_btn'
                    }

                }
                    @endif

            ]
        });

        $('#{{$id}}_excel_btn').on('click', function (){
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to generate excel file.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Generate'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generating...',
                        allowOutsideClick: false, // Disallow clicking outside the alert to close
                        allowEscapeKey: false, // Disallow closing the alert with the escape key
                        showCancelButton: false, // Hide the cancel button
                        showConfirmButton: false, // Hide the confirm button
                        onOpen: () => {
                            Swal.showLoading()
                        }
                    }).then(
                        $.ajax({
                            url: '{{$excelUrl}}',
                            type: 'GET',
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function (data) {
                                Swal.close();
                                console.log(data);
                                const url = window.URL.createObjectURL(new Blob([data]));
                                const link = document.createElement('a');
                                link.href = url;
                                link.setAttribute('download', '{{config('app.name')}} {{$id}}.xlsx');
                                document.body.appendChild(link);
                                link.click();

                                Swal.fire({
                                    icon: 'success',
                                    title: data.title,
                                    text: data.message,
                                })
                            },
                            error: function (data) {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.statusText,
                                    text: data.responseJSON.message,
                                })
                            }
                        })
                    )

                }
            });
        })
    });
    function remove(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to delete this item.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
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
                    url: '{{$removeUrl}}' + id,
                    type: 'DELETE',
                    success: function (data) {
                        console.log(data);

                        Swal.fire({
                            icon: 'success',
                            title: data.title,
                            text: data.message,
                        })

                        $('#{{$id}}').DataTable().ajax.reload();
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
    function edit(id){

    }
</script>
@stop
