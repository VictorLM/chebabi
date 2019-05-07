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

$("#tipo").change(function(){
    if($(this).val() == 'Motorista'){
        alert('AO CRIAR UM EVENTO DO TIPO "MOTORISTA", OUTRO EVENTO DO TIPO "CARRO" É CRIADO AUTOMATICAMENTE COM OS MESMOS DADOS DO PRIMEIRO EVENTO.');
    }else if($(this).val() == 'Reunião'){
        window.location.replace("/intranet/agenda/novo-evento/reuniao");
    }
});
