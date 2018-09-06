$('.agendar-btn').click(function(){

    var id = $(this).attr('data-link');

    var confirmar = confirm("Confirma o agendamento de uma seção de massagem rápida para o dia "+id+"?");

    if (confirmar == true) {
        console.log("CONFIRMADO!");
    } else {
        console.log("CANCELADO!");
        return false;
    }

});




