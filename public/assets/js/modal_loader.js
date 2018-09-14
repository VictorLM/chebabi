$(document).on('submit','form#form',function(){
    $('#loaderModal').modal({backdrop: 'static', keyboard: false});
    $('#loaderModal').modal('show');
});