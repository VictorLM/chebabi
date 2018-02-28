$(document).on('submit','form#form',function(){
    $('#loaderModal').modal('show');
    $('#loaderModal').modal({backdrop: 'static', keyboard: false});
});