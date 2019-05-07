$('#my-select').multiSelect();
$("#recorrencia-select").multipleSelect({
    selectAll: false,
    onClick: function(view) {
        if(view.label == "Evento único"){
            $("#recorrencia-select").multipleSelect("uncheckAll");
            $('#recorrencia-select').multipleSelect('disable');
            $('#recorrencia-select').multipleSelect({
                isOpen: false,
            });
            $(".ms-choice").text("Evento único");
        }
    }
});

$("#iniciodata").on('keyup change', function (){
    var iniciodata = $("#iniciodata").val();
    $("#terminodata").val(iniciodata);
});

$("#iniciohora").on('keyup change', function (){
    var iniciohora = $("#iniciohora").val();
    var terminohora = moment.utc(iniciohora,'kk:mm').add(30,'minutes').format('kk:mm');
    $("#terminohora").val(terminohora);
});

$('#local').change(function(){
    //$('#iniciodata,#iniciohora,#terminodata,#terminohora').val('');
    $('#agendados').hide(200);
    $('#tabela_agendados').empty();
    $('#h-tbl-agendados').empty();

    var value = $('#iniciodata').val();
    
    if(value.length == 10){
        if($('#local').val() == "Sala 1"){
            eventos_reuniao($('#iniciodata').val(),"Sala 1")
        }else if($('#local').val() == "Sala 2"){
            eventos_reuniao($('#iniciodata').val(),"Sala 2")
        }else if($('#local').val() == "Outro"){
            $('#local').hide();
            $('#local').attr('name','');
            $("#local").prop('required',false);
            $('#local_text').show();
            $('#local_text').attr('name','local');
            $("#local_text").prop('required',true);
            $("#local_text").focus();
        }
    }
});

$('#iniciodata').change(function(){
    var value = $('#iniciodata').val();
    if(value.length == 10){
        if($('#local').val() != ''){
            $('#tabela_agendados').empty();
            $('#h-tbl-agendados').empty();
            $('#agendados').hide(200);
            //////////////////////////
            if($('#local').val() == "Sala 1"){
                eventos_reuniao($('#iniciodata').val(),"Sala 1")
            }else if($('#local').val() == "Sala 2"){
                eventos_reuniao($('#iniciodata').val(),"Sala 2")
            }
        }else{
            alert("Primeiro, indique o local.");
            $("#local").focus();
        }
    }
});

//AJAX REUNIÕES JÁ AGENDADAS NA DATA SELECIONADA
function eventos_reuniao(data,sala) {
    $.ajax({
        type: "POST",
        url: '/intranet/agenda/checagem_salas_reuniao',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        data: {data: data, sala : sala},
        success: function(response){
            eventos_reuniao_response(response);
        }
    })
};

function eventos_reuniao_response(response) {
    var obj = JSON.parse(response);
    if(Object.keys(obj).length > 0){
        var agendados = '<thead><tr><th scope="col">Título</th><th scope="col">Início</th><th scope="col">Término</th><th scope="col">Local</th></tr></thead>';
        $.each(obj, function(key,val){
            agendados += '<tr><td><a href="/intranet/agenda/evento/'+val['id']+'" target="_blank">'+val['title']+'</a></td><td>'+moment(val['start']).format('L LT')+'</td><td>'+moment(val['end']).format('L LT')+'</td></td><td>'+val['local']+'</td></tr>';
        });
        $('#h-tbl-agendados').empty();
        $('#tabela_agendados').append(agendados);
        $('#h-tbl-agendados').append("Reuniões já agendadas nesta data e sala.");
    }else{
        $('#h-tbl-agendados').empty();
        $('#h-tbl-agendados').append("Nenhuma reunião agendada nesta data e sala.");
    }
    $('#agendados').show(200);
}
//~//