$(document).ready(function(){
    legalone_api_call_credores();
    legalone_api_call_tipo_gasto();
});

$("#valor").on('keyup change', function (){
    var firstChar = $('#valor').val().substr(0, 1);
    if(firstChar != "-"){
        $(this).val('-' + $(this).val());
    }
});

$("#pesquisar").click( function (){
    if($('#pasta').val() == ''){
        alert("Insira o número da pasta!");
        $("#pasta").focus();
        return false;
    }else{
        legalone_api_call();
        $("#pastaid").val("");
        $("#display").empty();
        $("#display").show();
    }
});

$(document).on('click', "button.linkpastas", function() {
    var id = $(this).val();
    $("#pasta").val($(this).text());
    $("#pastaid").val(id);
    $('#pasta').prop('readonly', true);
    $('#pesquisar').prop('disabled', true);
    $("#display").hide();
});

$(document).on('submit','form#form',function(){
    
    if($('#pastaid').val() == ''){
        alert("Preencha o campo pasta, pesquise e selecione a pasta desejada no resultado da pesquisa!");
        return false;
    }

    $('#loaderModal').modal('show');
    $('#loaderModal').modal({backdrop: 'static', keyboard: false});
});

//////////////////////////////////////////////////////////////////////////
// -------------- CHAMADA E CALLBACK API LEGAL ONE ------------------ //
//////////////////////////////////////////////////////////////////////////

//CHAMADA AJAX API LEGALONE
function legalone_api_call() {
    $.ajax({
        type: "POST",
        url: './get-pasta-id',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        data: $("#pasta").val(),
        beforeSend: function() {
            $('#loaderpasta').css('visibility', 'visible');
        },
        complete: function() {
            $('#loaderpasta').css('visibility', 'hidden');
        },
        success: function(response){
            var resposta = JSON.parse(response);
            //console.log(obj['value']);
            if (resposta['value'].length == 0){   
                alert("Pasta não encontrada! Tente novamente. Se o erro persistir, informe o departamento responsavel.");
            }
            else{   
                legalone_api_response(resposta);
            }
        }
    })
};

function legalone_api_response(response) {
    $.each(response, function(key,val){
        var options = '';
        $.each(val, function(key,val){
            options += 
            '<button type="button" class="linkpastas list-group-item" value="'+val.id+'">'+val.folder+'</button>';
        });
        $('#display').append(options);
    });
}
//////////
function legalone_api_call_credores() {
    $.ajax({
        type: "POST",
        url: './get-credores',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        data: "",
        beforeSend: function() {
            $('#loadercredor').css('visibility', 'visible');
        },
        complete: function() {
            $('#loadercredor').css('visibility', 'hidden');
        },
        success: function(response){
            if (response == null){   
                alert("Credores não encontrados! Tente novamente. Se o erro persistir, informe o departamento responsavel.");
            }else{
                var resposta = JSON.parse(response);
                legalone_api_response_credores(resposta);
            }
        }
    })
};

function legalone_api_response_credores(response) {
    var options = '';
    $.each(response, function(key,val){
        options += 
        '<option value="'+val.id+'">'+val.name+'</option>';
    });
    $('#credor').append(options);
}

function legalone_api_call_tipo_gasto() {
    $.ajax({
        type: "POST",
        url: './get-tipo-gasto',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: "html",
        data: "",
        beforeSend: function() {
            $('#loadertipogasto').css('visibility', 'visible');
        },
        complete: function() {
            $('#loadertipogasto').css('visibility', 'hidden');
        },
        success: function(response){
            if (response == null){   
                alert("Tipo de gasto não encontrado! Tente novamente. Se o erro persistir, informe o departamento responsavel.");
            }else{
                var resposta = JSON.parse(response);
                legalone_api_response_tipo_gasto(resposta);
            }
        }
    })
};

function legalone_api_response_tipo_gasto(response) {
    var options = '';
    //console.log(response.id);
    options = '<option value="'+response.id+'" selected>Gastos com Clientes / '+response.name+'</option>';
    $('#tipo').append(options);
}