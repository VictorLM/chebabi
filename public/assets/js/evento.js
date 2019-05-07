$('.cancelar-btn').click(function(){
    var confirmar = confirm("Confirma o cancelamento desse evento?");
    if(confirmar == true) {
        //ENVIA FORM
        $('#loaderModal').modal({backdrop: 'static', keyboard: false});
        $('#loaderModal').modal('show');
    }else{
        return false;
    }
});