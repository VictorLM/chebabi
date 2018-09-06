$(function(){
    $('#nome').keyup(function(){
        var val = $(this).val().toLowerCase();
        $("#contatos tr").hide();
        $("#contatos tr").each(function(){
            var text = $(this).text().toLowerCase();
            if(text.indexOf(val) != -1){
                $(this).show();
            }
        });
        //MARK.JS//
        var context = document.querySelectorAll("#contatos");
        var instance = new Mark(context);
        instance.unmark();
        if($("#nome").val() != ""){
            instance.mark($("#nome").val());
        }
    });
});