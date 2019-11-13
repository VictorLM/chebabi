$(document).ready(function(){

    if ($(window).width() < 960) {
        var placement = 'bottom';
    }else {
        var placement = 'right';
    }

    $('#agendamentos').popover({
        placement: placement,
        html: true,
        template: '<div class="popover container"><div class="arrow"></div><a href="#" class="list-group-item" data-toggle="modal" data-target="#quickModal">QUICK MASSAGE</a><a href="#" class="list-group-item" data-toggle="modal" data-target="#auriculoModal">AURICULOTERAPIA</a><a href="#" class="list-group-item" data-toggle="modal" data-target="#pesModal">MASSAGEM NOS PÉS</a></div>'
    });

    ajax_terapias_charts();

});

function ajax_terapias_charts() {
    $.ajax({
        type: "GET",
        url: '/intranet/terapias/painel-admin/terapias-charts',
        success: function(response){
            foreach_render(response);
        }
    })
};

function foreach_render(response) {

    $.each(response,function(index, value){

        if(index == "resumo_terapias"){
            terapias_column_chart_render(value, index, "Resumo agendamentos");
        }else if(index == "agendamentos_por_usuario"){
            terapias_column_rotated_labels_chart_render(value, index, "Agendamentos por usuário - Top 10");
        }else{
            terapias_pie_chart_render(value, index);
        }
        
    });

};

function terapias_pie_chart_render(data, tipo) {

    var options = {
        chart: {
            width: 400,
            type: 'pie',
        },
        labels: Object.keys(data),
        series: Object.values(data),
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 199
                },
                legend: {
                    position: 'bottom'
                },
            }
        }]
    }

    var chart = new ApexCharts(
        document.querySelector("#chart_div_"+tipo),
        options
    );

    chart.render();
}

function terapias_column_rotated_labels_chart_render(data, tipo, titulo) {

    var options = {
        chart: {
            height: 220,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                columnWidth: '50%',
                endingShape: 'rounded'	
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2
        },
        series: [{
            name: 'Sessões',
            data: Object.values(data)
        }],
        grid: {
            row: {
                colors: ['#fff', '#f2f2f2']
            }
        },
        xaxis: {
            labels: {
                rotate: -45
            },
            categories: Object.keys(data)
        },
        yaxis: {
            title: {
                text: titulo,
            },
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 0.85,
                stops: [50, 0, 100]
            },
        },

    }

    var chart = new ApexCharts(
        document.querySelector("#chart_div_"+tipo),
        options
    );

    chart.render();
}

function terapias_column_chart_render(data, tipo, titulo) {

    var colors = ['#008FFB', '#FF4560', '#FEB019'];

    var options = {
        chart: {
            height: 220,
            type: 'bar',
        },
        colors: colors,
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '80%',
                endingShape: 'rounded'	
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [
            {
                name: Object.keys(data.sessoes)[0],
                data: Object.values(data.sessoes)[0]
            }, {
                name: Object.keys(data.sessoes)[1],
                data: Object.values(data.sessoes)[1]
            }, {
                name: Object.keys(data.sessoes)[2],
                data: Object.values(data.sessoes)[2]
            }
        ],
        xaxis: {
            categories: data.terapias,
        },
        yaxis: {
            title: {
                text: titulo
            }
        },
        fill: {
            opacity: 1

        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart_div_"+tipo),
        options
    );

    chart.render();
}