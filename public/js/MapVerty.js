
class Doughnut{
    constructor(elementId, options, id) {
        this.id = id;
        this.elementID =  elementId;
        this.options = options;
        this.chart = null;
    }

    featchData(url) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    resolve(data);
                },
                error: function (error) {
                    reject(error);
                }
            });
        })
    }

    createChart(labels, data) {
        const config = {
            type: 'doughnut',
            data: {
                labels: labels,

                datasets: [
                    {
                        data: data,
                    }
                ],
            },
            plugins: [ChartDataLabels],
            options: this.options,
        }

        const ctx = document.getElementById(this.elementID).getContext('2d');
        this.chart = new Chart(ctx,  config);
    }

    updateChart(url) {
        this.featchData(url).then(data => {

            if(data.empty === true) {
                $('#warning-'+this.id+'-text').text('No data available');
            }

            const labels = data.labels;
            const datasets = Object.values(data.data);

            var validation = 0 ;

            datasets.forEach((element, index) => {
                validation += element;

            });

            if(validation === 0) {
                $('#warning-'+this.id+'-text').text('No data available');
            }

            if(this.chart) {
                this.chart.data.labels = labels;
                this.chart.data.datasets[0].data =datasets;
                this.chart.update();
            }else{
                this.createChart(labels, datasets);
            }
        }).catch((error) => {
                console.log(error);
        });
    }


}

class Line{
    constructor(elementId, options, id) {
        this.id = id;
        this.elementID =  elementId;
        this.options = options;
        this.chart = null;
    }

    featchData(url) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    resolve(data);
                },
                error: function (error) {
                    reject(error);
                }
            });
        })
    }

    createChart(labels, data) {
        const config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: data,
            },
            plugins: [ChartDataLabels],
            options: this.options,
        }

        const ctx = document.getElementById(this.elementID).getContext('2d');
        this.chart = new Chart(ctx,  config);
    }

    updateChart(url) {
        this.featchData(url).then(data => {

            if(data.empty === true) {
                $('#warning-'+this.id+'-text').text('No data available');
            }

            const labels = data.data.labels;
            const datasets = Object.values(data.data.datasets);


            var validation = 0 ;

            datasets.forEach((element, index) => {
                validation += element;


            });

            if(validation === 0) {
                $('#warning-'+this.id+'-text').text('No data available');
            }

            if(this.chart) {
                this.chart.data.labels = labels;
                this.chart.data.datasets[0].data =datasets;
                this.chart.update();
            }else{
                this.createChart(labels, datasets);
            }
        }).catch((error) => {
            console.log(error);
        });
    }
}
class Bar{
    constructor(elementId, options, id) {
        this.id = id;
        this.elementID =  elementId;
        this.options = options;
        this.chart = null;
    }

    featchData(url) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    resolve(data);
                },
                error: function (error) {
                    reject(error);
                }
            });
        })
    }
    createChart(labels, data) {
        const config = {
            type: 'bar',
            data: data,
            plugins: [ChartDataLabels],
            options: this.options,
        }

        const ctx = document.getElementById(this.elementID).getContext('2d');
        this.chart = new Chart(ctx,  config);
    }

    updateChart(url) {
        this.featchData(url).then(data => {
            var HorBarChartLabels = [];
            var HorBarChartData = [];
            var HorBarStudentsChartData = [];
            var HorBarColor = [];

            var sorted = data.data.sort((a, b) => {
               return b.count - a.count;
            });

            $.each(sorted, function(index, value) {
                HorBarChartLabels.push(value.municipality);
                HorBarChartData.push(((value.count/value.total)*100).toFixed(2));
                HorBarStudentsChartData.push(value.total);
                $('#barOverAllStudent').text(value.overall);
                HorBarColor.push('#'+(Math.random()*0xFFFFFF<<0).toString(16));
            });

            if(data.data.empty === true) {
                $('#warning-' + this.id + '-text').text('No data available');
            }

            const labels = HorBarChartLabels;
            const datasets = {
                labels: HorBarChartLabels,
                datasets: [
                    {
                        label: 'Poverty Index',
                        data: HorBarChartData,
                        backgroundColor: '#ffc107',
                        datalabels:{
                            formatter: (value, ctx) => {
                                return value + '%'; // Format the value as a percentage
                            },
                            anchor: "end",
                            align: 'start',
                            color: '#fff',
                        },
                    },
                    {
                        label: 'Student Population',
                        data: HorBarStudentsChartData,
                        backgroundColor:'#28a745',
                        datalabels: {
                            color: '#fff',
                        },
                    }
                ]
            };

            if(this.chart) {
                this.chart.data.labels = labels;
                this.chart.data.datasets[0].data =datasets;
                this.chart.update();
            }else{
                this.createChart(labels, datasets);
            }
        }).catch((error) => {
            console.log(error);
        });
    }
}
