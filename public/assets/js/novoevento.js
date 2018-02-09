$('#my-select').multiSelect();

$("#iniciodata").on('keyup change', function (){

    var iniciodata = $("#iniciodata").val();
    $("#terminodata").val(iniciodata);

});

$("#iniciohora").on('keyup change', function (){

    var iniciohora = $("#iniciohora").val();

    var terminohora = moment.utc(iniciohora,'kk:mm').add(30,'minutes').format('kk:mm');

    $("#terminohora").val(terminohora);

});