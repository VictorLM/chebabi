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
    }else if($('#pasta').val() == '' || $('#tipo').val() == '' || $('#descricao').val() == ''){
        alert("Preencha corretamente os campos obrigatórios!");
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
    //var obj = JSON.parse(response);
    $.each(response, function(key,val){
        var options = '';
        $.each(val, function(key,val){
            options += 
            '<button type="button" class="linkpastas list-group-item" value="'+val.id+'">'+val.folder+'</button>';
        });
        $('#display').append(options);
    });
}