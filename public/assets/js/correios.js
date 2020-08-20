$(document).ready(function(){
    
    $("[name='reembolsavel']").change( function (){

        toggleInputMotivo($(this).val());

    });

    $("[name='cep']").change( function (){ 

        const cep = ($(this).val()).replace(/\D/g, "");

        if(cep.length == 8) {

            try {
                $.get("https://viacep.com.br/ws/" + cep + "/json/", function(data) {

                    if(!data.erro) {
                        $("[name='rua']").val(data.logradouro);
                        $("[name='cidade']").val(data.localidade);
                        $("[name='estado']").val(data.uf);
                        $("[name='numero']").focus();
                    }

                });
            } catch(e) {
                console.log(e);
            }

        } else {
            // CEP INVÁLIDO
            alert("CEP Inválido!");
            $(this).val("");
        }

    });

});


function toggleInputMotivo(reembolsavel) {
        
    if(reembolsavel == '0') {

        $("#reembolsavel-div").removeClass('col-md-6').addClass('col-md-3');
        $("#tipo-div").removeClass('col-md-6').addClass('col-md-3');
        $("[name='motivo']").val("");
        $("[name='motivo']").prop('required', true);
        $("#motivo-div").show();
        $("[name='motivo']").focus();

    } else {

        $("#motivo-div").hide();
        $("#reembolsavel-div").removeClass('col-md-3').addClass('col-md-6');
        $("#tipo-div").removeClass('col-md-3').addClass('col-md-6');
        $("[name='motivo']").val("");
        $("[name='motivo']").prop('required', false);
        
    }
    
}