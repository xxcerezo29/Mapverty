<div class="card card-primary card-outline">
    <div class="card-body">
        <h2 class="text-uppercase">{{$title}}</h2>
        <div style="width: 100%; height: 500px">
            <canvas id="{{$id}}-doughnut"></canvas>
            <h4 id="warning-{{$id}}-text"></h4>

        </div>
        <p><span class="text-danger">Note: </span>Overall Summary of {{$title}} of all students</p>
    </div>
</div>

@push('js')
    <script>
        $(function (){
            var options = {
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        align: 'start'
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage =  (value*100 / sum).toFixed(2)+"%";
                            if(percentage !== '0.00%'){
                                return percentage;
                            }
                        },
                        color: '#fff',
                    }
                },
                animation: {
                    onComplete: function (animation){
                        var firstSet = animation.chart.config.data.datasets[0].data;
                        var sum = firstSet.reduce(function(a, b){
                            return a + b;
                        }, 0);
                    }
                }
            }
            const {{$id}}_doughnut = new Doughnut('{{$id}}-doughnut', options, '{{$id}}');

            {{$id}}_doughnut.updateChart('{{ $ajaxURL }}');
        })
    </script>
@endpush
