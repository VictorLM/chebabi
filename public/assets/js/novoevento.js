$('#my-select').multiSelect();
$("#recorrencia-select").multipleSelect({
    selectAll: false,
    onClick: function(view) {
        if(view.label == "Evento único"){
            $("#recorrencia-select").multipleSelect("uncheckAll");
            $('#recorrencia-select').multipleSelect('disable');
            $('#recorrencia-select').multipleSelect({
                isOpen: false,
            });
            $(".ms-choice").text("Evento único");
        }
    }
});


$("#iniciodata").on('keyup change', function (){

    var iniciodata = $("#iniciodata").val();
    $("#terminodata").val(iniciodata);

});

$("#iniciohora").on('keyup change', function (){

    var iniciohora = $("#iniciohora").val();

    var terminohora = moment.utc(iniciohora,'kk:mm').add(30,'minutes').format('kk:mm');

    $("#terminohora").val(terminohora);

});