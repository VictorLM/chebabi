$(document).ready(function(){

    function uau_lido(uau_id) {
        $.ajax({
            type: "POST",
            url: './uau_lido',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            data: uau_id
        })
    };
    
    $(".uaunaolido").click(function(){
        uau_id = $(this).find("span").text();
        uau_lido(uau_id);
        $(".fechar").click(function(){
            window.location.reload();
        });
    });

});
