<div class="card">
    <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">{{$title}}</h3>
        </div>
    </div>
    <div class="card-body p-2">
        <div class="chart-responsive">
            <label for="course">Year</label>
            <select name="year"
                    class="custom-select form-control-border "
                    id="year-selector">
                <option value=" "></option>
            </select>
            <p><span class="text-danger">Overall Student: </span> <span class="text-info" id="barOverAllStudent">0</span></p>
            <canvas id="{{$id}}-bar" class="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            <h4 id="warning-{{$id}}-text"></h4>
            <br>
            <p id="indicesSummary"></p>

            <br>
            <p><span class="text-danger">Note:</span> (Poverty Index) Measure the extent and severity of poverty in a municipalities.</p>
            <p><span class="text-danger">Note:</span> (Student Population) Measure the overall student enrolled in ISU-Santiago Extension living in a municipalities.</p>
        </div>
    </div>
</div>
@push('js')
    <script>
        $(function (){
            var options = {
                indexAxis: 'y',
                elements: {
                    bar: {
                        borderWidth: 0,
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Poverty Indices'
                    }
                }
            }

            const {{$id}}_bar = new Bar('{{$id}}-bar', options, '{{$id}}');

            {{$id}}_bar.updateChart('{{ $ajaxURL }}?year_selector=2023');

            $('#year-selector').on('change', function (){
                data = $('#year-selector').val()
                {{$id}}_bar.updateChart('{{ $ajaxURL }}?year_selector='+data);
            })

        })
    </script>
@endpush
