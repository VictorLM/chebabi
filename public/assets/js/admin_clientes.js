$('.del-btn').click(function(){
    var confirmar = confirm("Confirmar a exclusão desse cliente?");
    if(confirmar == true) {
        //ENVIA FORM
    }else{
        return false;
    }
});