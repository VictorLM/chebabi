$('.agendar-btn').click(function(){
    var id = $(this).attr('data-link');
    var confirmar = confirm("Confirma o agendamento de uma seção de terapia no o dia "+id+"?");
    if(confirmar == true) {
        //ENVIA FORM
        $('#loaderModal').modal({backdrop: 'static', keyboard: false});
        $('#loaderModal').modal('show');
    }else{
        return false;
    }
});

$('.cancelar-btn').click(function(){
    var id = $(this).attr('data-link');
    var confirmar = confirm("Confirma o cancelamento de uma seção de terapia no o dia "+id+"?");
    if(confirmar == true) {
        //ENVIA FORM
        $('#loaderModal').modal({backdrop: 'static', keyboard: false});
        $('#loaderModal').modal('show');
    }else{
        return false;
    }
});