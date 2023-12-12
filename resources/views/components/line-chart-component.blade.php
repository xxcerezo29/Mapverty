
    <div class="card">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between">
                <h3 class="card-title">{{$title}}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <p class="d-flex flex-column">
{{--                    <span class="text-bold text-lg">{{$description}}</span>--}}
                </p>
            </div>
            <div class="position-relative mb-4">
                <canvas id="{{$id}}-linechart" height="200"></canvas>
                <h4 id="warning-{{$id}}-text"></h4>
            </div>
            <div class="d-flex flex-row justify-content-end">
                      <span class="mr-2">
                        <i class="fas fa-square text-danger"></i> Below Poverty Line
                      </span>
                <span>
                            <i class="fas fa-square text-success"></i> Above Poverty Line
                        </span>
            </div>
            <p><span class="text-danger">Note: </span>Summary graph from previous to current data of students below and above the poverty line</p>
        </div>
    </div>
@push('js')
    <script>
        $(function (){
            var options = {
            }

            const {{$id}}_line = new Line('{{$id}}-linechart', options, '{{$id}}');

            {{$id}}_line.updateChart('{{ $ajaxURL }}');

        })
    </script>
@endpush

