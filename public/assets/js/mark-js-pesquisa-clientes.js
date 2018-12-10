$(function(){
    $('#pesquisa-mark-js').keyup(function(){
        var val = $(this).val().toLowerCase();
        $(".cliente").hide();
        $(".cliente").each(function(){
            var text = $(this).text().toLowerCase();
            if(text.indexOf(val) != -1){
                $(this).show();
            }
        });
        //MARK.JS//
        var context = document.querySelectorAll(".cliente");
        var instance = new Mark(context);
        instance.unmark();
        if($("#pesquisa-mark-js").val() != ""){
            instance.mark($("#pesquisa-mark-js").val());
        }
    });
});