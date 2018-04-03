$(document).ready(function(){

    if ($('#descricao').val() != ''){
        quill.root.innerHTML = $('#descricao').val();
    }

    $(document).on('submit','form#form',function(){
        var html = quill.root.innerHTML;
        $('#descricao').val(html);

        $('#loaderModal').modal('show');
        $('#loaderModal').modal({backdrop: 'static', keyboard: false});
    });

});