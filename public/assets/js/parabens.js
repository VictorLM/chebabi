$(document).ready(function(){

    function parabens_lido(parabens_id) {
        $.ajax({
            type: "POST",
            url: './parabens_lido',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            data: parabens_id
        })
    };
    
    $(".parabens-nao-lido").click(function(){
        parabens_id = $(this).find("span").text();
        parabens_lido(parabens_id);
        $(".fechar").click(function(){
            window.location.reload();
        });
    });

});
