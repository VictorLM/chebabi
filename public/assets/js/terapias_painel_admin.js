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

    ajax_terapias_charts("","");
    //$("#graficosModal").modal("show");

});

function ajax_terapias_charts(mes,ano) {
    var url;
    if(mes && ano){
        url = '/intranet/terapias/painel-admin/terapias-charts?mes='+mes+'&ano='+ano;
    }else{
        url = '/intranet/terapias/painel-admin/terapias-charts';
    }
    $.ajax({
        type: "GET",
        url: url,
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
        }else if(index == "sessoes_por_usuario_e_por_tipo_terapia"){
            terapias_stacked_bar_chart_render(value, index);
        }
    });
};

$("#filtrar-terapias-charts").click(function(){
    var mes = $("#mes").val();
    var ano = $("#ano").val();
    ajax_terapias_charts(mes,ano);
});

function terapias_column_chart_render(data, tipo, titulo) {

    $("#chart_div_"+tipo).empty();

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

function terapias_column_rotated_labels_chart_render(data, tipo, titulo) {

    $("#chart_div_"+tipo).empty();

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

function terapias_stacked_bar_chart_render(data, tipo) {

    $("#chart_div_"+tipo).empty();

    var options = {
        chart: {
            height: 380,
            type: 'bar',
            stacked: true,
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
            
        },
        stroke: {
            width: 1,
            colors: ['#fff']
        },
        series: [
            {
                name: Object.keys(data.terapias)[0],
                data: Object.values(data.terapias)[0]
            },
            {
                name: Object.keys(data.terapias)[1],
                data: Object.values(data.terapias)[1]
            },
            {
                name: Object.keys(data.terapias)[2],
                data: Object.values(data.terapias)[2]
            },
        ],
        xaxis: {
            categories: Object.values(data.users),
        },
        yaxis: {
            title: {
                text: undefined
            },
        },
        fill: {
            opacity: 1
            
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart_div_"+tipo),
        options
    );
    
    chart.render();
}