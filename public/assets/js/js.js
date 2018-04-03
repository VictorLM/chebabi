$(document).ready(function(){
    
    //////////////////////////////////////////////////////////////////////////
    // ------------------ FUNÇÕES DO RELATÓRIO DE VIAGEM ------------------ //
    //////////////////////////////////////////////////////////////////////////
    
    //AJUSTES INPUTS TIPO DE VIAGEM
    ajustesInputs1 = function(){ 
        //RESETA FORM
        //$('#form_relatorio').find('input, select').not("#tipo_viagem, #usuario, #reembolsavel, [name='_token']").val('');
        if( $('#tipo_viagem').val() == '1'){
            //MOSTRA O INPUT VEÍCULO E SETA O REQUIRED P/ TRUE
            $('#infos_gerais_append').css('display', 'block');
            $(".gerais_requireds").prop('required',true);
            //MOSTRA OS INPUTS INFORMAÇÕES DA VIAGEM E SETA O REQUIRED P/ TRUE
            $('.infos_viagem').css('display', 'block');
            $('#enda, #endb, #endc, #totalkm').prop('required',true);
            //ALTERA CLASSE PAR AJUSTAR O TAMANHO DAS DIVS
            $('.data').addClass('col-md-3').removeClass('col-md-6');
            $('.caucao').addClass('col-md-6').removeClass('col-md-12');
        }else{
            //ESCONDE OS INPUTS VEÍCULO E REEMBOLSO, RESETA E SETA O REQUIRED P/ FALSE
            $('#infos_gerais_append').css('display', 'none');
            $('.gerais_requireds').prop('selectedIndex',0);
            $('.gerais_requireds').prop('required',false);
            //RESETA INPUT PEDÁGIO TAMBÉM
            $('.pedagio').css('display', 'none');
            $('.pedagio_required').prop('selectedIndex',0);
            $('.pedagio_required').prop('required',false);
            //ESCONDE OS INPUTS INFORMAÇÕES DA VIAGEM, RESETA E SETA O REQUIRED P/ FALSE
            $('.infos_viagem').css('display', 'none');
            $('#enda, #endb, #endc, #endd, #totalkm, #valorkm').val("");
            $('#enda, #endb, #endc, #totalkm, #valorkm').prop('required',false);
            //ALTERA CLASSE PAR AJUSTAR O TAMANHO DAS DIVS
            $('.data').addClass('col-md-6').removeClass('col-md-3');
            $('.caucao').addClass('col-md-12').removeClass('col-md-6');
        }
    };
    
    //AJUSTES INPUT PEDÁGIO
    ajustesInputs2 = function(){ 
        //RESETA FORM
        //$('#form_relatorio').find('input, select').not("#carro, #tipo_viagem, #usuario, #reembolsavel, [name='_token']").val('');
        if( $("#carro").val() == 'Escritório'){
            $('.pedagio').css('display', 'block');
            $('.pedagio_required').prop('required',true);
            //ALTERA CLASSE PAR AJUSTAR O TAMANHO DAS DIVS
            $('.carro').addClass('col-md-6').removeClass('col-md-12');
        }else{
            $('.pedagio').css('display', 'none');
            $('.pedagio_required').prop('selectedIndex',0);
            $('.pedagio_required').prop('required',false);
            //ALTERA CLASSE PAR AJUSTAR O TAMANHO DAS DIVS
            $('.carro').addClass('col-md-12').removeClass('col-md-6');
        }
    };
    
    //MOSTRA OS INPUTS ADICIONAIS DE DESPESAS
    $('#add-despesa').click(function(){
        var next = false;
        var i = 1;
        while (!next){
            if($('.despesas'+i).css('display') == 'none'){
                $('.despesas'+i).css('display', 'block');
                next = true;
            }
            i++;
            if(i>4){
                next = true;
            }
        }
    });
    //ESCONDE OS INPUTS ADICIONAIS DE DESPESAS
    $('#del-despesa').click(function(){
        var next = false;
        var i = 4;
        while (!next){
            if($('.despesas'+i).css('display') == 'block'){
                $('.despesas'+i).css('display', 'none');
                //RESETA INPUTS
                $("input[name=descricaodispesa"+i+"]").val("");
                $("input[name=despesapasta"+i+"]").val("");
                $("input[name=cliente"+i+"]").val("");
                $("input[name=despesasgerais"+i+"]").val("");
                next = true;
            }
            i--;
            if(i<1){
                next = true;
            }
        }
    });
    //MOSTRA OS INPUTS ADICIONAIS DE CLIENTE E DESCRIÇÃO DE VIAGEM
    $('#add-cliente').click(function(){
        var next = false;
        var i = 1;
        while (!next){
            if($('.clientes'+i).css('display') == 'none'){
                $('.clientes'+i).css('display', 'block');
                $('.descricao-viagem'+i).css('display', 'block');
                //SETA REQUIRED TRUE
                $("input[name=pasta"+i+"]").prop('required',true);
                $("input[name=cliente"+i+"]").prop('required',true);
                $("input[name=contrario"+i+"]").prop('required',true);
                $("input[name=proc"+i+"]").prop('required',true);
                $("input[name=motivoviagem"+i+"]").prop('required',true);
                next = true;
            }
            i++;
            if(i>3){
                next = true;
            }
        }
    });
    //ESCONDE OS INPUTS ADICIONAIS DE CLIENTES E DESCRIÇÃO DE VIAGEM
    $('#del-cliente').click(function(){
        var next = false;
        var i = 4;
        while (!next){
            if($('.clientes'+i).css('display') == 'block'){
                $('.clientes'+i).css('display', 'none');
                $('.descricao-viagem'+i).css('display', 'none');
                //RESETA INPUTS
                $("input[name=pasta"+i+"]").val("");
                $("input[name=cliente"+i+"]").val("");
                $("input[name=contrario"+i+"]").val("");
                $("input[name=proc"+i+"]").val("");
                $("input[name=motivoviagem"+i+"]").val("");
                //SETA REQUIRED FALSE
                $("input[name=pasta"+i+"]").prop('required',false);
                $("input[name=cliente"+i+"]").prop('required',false);
                $("input[name=contrario"+i+"]").prop('required',false);
                $("input[name=proc"+i+"]").prop('required',false);
                $("input[name=motivoviagem"+i+"]").prop('required',false);
                next = true;
            }
            i--;
            if(i<1){
                next = true;
            }
        }
    });
    
    calculaKm = function(){  
        var totalkm = $('#totalkm').val();
        var valorKm = totalkm*0.8;
        if ($('#carro').val() == "Particular"){
            $("#valorkm").val(valorKm.toFixed(2));
        }
        else if ($('#carro').val() == "Escritório"){
            $("#valorkm").val(0);
        }else {
            if($('#tipo_viagem').val() == '1'){
                alert("Selecione se o carro usado foi particular ou do escritório.");
                $("#carro").focus();
            }
        }
    };
    
    calculaDespesas = function(){
        var valorDespesas = 
            (parseFloat($("#despesasgerais1").val()) || 0.0 ) +
            (parseFloat($("#despesasgerais2").val()) || 0.0 ) +
            (parseFloat($("#despesasgerais3").val()) || 0.0 ) +
            (parseFloat($("#despesasgerais4").val()) || 0.0 );
        $("#totalgastos").val(valorDespesas.toFixed(2));
    };
    
    calculaValor = function(){
        var valorCaucao = (parseFloat($("#caucao").val()) || 0.0 );
        var valorKm = (parseFloat($("#valorkm").val()) || 0.0 );
        var valorGastos = (parseFloat($("#totalgastos").val()) || 0.0 );
        valorDev = (valorGastos - valorCaucao) + valorKm;
        $("#aserdevolvido").val(valorDev.toFixed(2));
    };
    
    //////////////////////////////////////////////////////////////////////////
    // ------  CHAMADAS E GATILHOS DAS FUNÇÕES DO RELATÓRIO DE VIAGEM ----- //
    //////////////////////////////////////////////////////////////////////////

    ajustesInputs1();
    ajustesInputs2();

    $('#tipo_viagem').change(function(){
       ajustesInputs1();
    });
    
    $('#carro').change(function(){
        ajustesInputs2();
    });
    
    $(".calcend").change(function(){
        if($('#tipo_viagem').val() == '1'){
            calculaDistancia();
        }
    });
    
    $(".calcdespesas").on('keyup change', function (){
        calculaDespesas();
        $('#caucao').val(0);
    });

    $("#caucao, .calcdespesas").on('keyup change', function (){
        calculaDespesas();
        calculaValor();
    });
    
    $(".pesquisar").click( function (){
        if($('#tipo_viagem').val() == ''){
            alert("Selecione o tipo de viagem!");
            $("#tipo_viagem").focus();
            return false;
        }else{
            var id = $(this).val();
            legalone_api_call(id);
            $("#contrario"+id).val("");
            $("#cliente"+id).val("");
            $("#proc"+id).val("");
            $("#display"+id).empty();
            $("#display"+id).show();
        }
    });
    
    $("#enda").keyup(function(){
        if($('#carro').val() == ''){
            alert("Selecione o tipo de veículo utilizado!");
            $("#carro").focus();
            return false;
        }
        
        if($('#reembolsavel').val() == ''){
            alert("Selecione se a viagem é reembolsável!");
            $("#reembolsavel").focus();
            return false;
        }
        
        if($('#carro').val() == "Escritório"){
            if($('#pedagio').val() == ''){
                alert("Selecione se houve pedágio no percurso!");
                $("#pedagio").focus();
                return false;
            }
        }
    });

    $(document).on('click', "button.linkpastas", function() {
        var id = $(this).val();
        $("#pasta"+id).val($(this).text());
        $("#proc"+id).val($("."+$(this).text()+"-proc"+id).text());
        
        var contactId = $("."+$(this).text()+"-customer"+id).text();
        if(contactId != "" && contactId != null){
            var tipo = "Customer";
            legalone_api_call_contacts(contactId, tipo, id);
        }
        
        var contactId = $("."+$(this).text()+"-otherparty"+id).text();
        if(contactId != "" && contactId != null){
            var tipo = "OtherParty";
            legalone_api_call_contacts(contactId, tipo, id);
        }
        
        $("#display"+id).hide();
    });
    
    //CHECAGEM GERAL ANTES DE SUBMETER O FORMULÁRIO
    $(document).on('submit','form#form_relatorio',function(){
        if ($('#totalkm').val() == '' || $('#valorkm').val() == ''){
            if($('#tipo_viagem').val() == '1'){
                alert("Preencha corretamente ao menos os campos Endereço A, B e C. \n\
                Sendo A sua origem, B seu destino e C seu retorno.");
                $('#enda, #endb, #endc, #endd').val('');
                $("#enda").focus();
                return false;
            }
        }
        if($('#totalgastos').val() == ''){
            calculaDespesas();
            alert("Erro! Confira se preencheu os campos corretamente e tente novamente!");
            return false;
        }
        if($('#caucao').val() == ''){
            $('#caucao').val(0);
            alert("Valor de caução calculado como 0. Envie novamente!");
            calculaKm();
            calculaDespesas();
            calculaValor();
            return false;
        }
        if($('#aserdevolvido').val() == ''){
            calculaValor();
            alert("Erro! Confira se preencheu os campos corretamente e tente novamente!");
            return false;
        }
        
        $('#loaderModal').modal('show');
        $('#loaderModal').modal({backdrop: 'static', keyboard: false});
    });

    //////////////////////////////////////////////////////////////////////////
    // -------- CHAMADAS E CALLBACKS APIS GOOGLE DISTANCE MATRIX  --------- //
    //////////////////////////////////////////////////////////////////////////
    
    //CHAMADA API GOOGLE DISTANCE MATRIX
    calculaDistancia = function(){
        var origin1 = $("#enda").val();
        var destinationA = $("#endb").val();
        var origin2 = $("#endb").val();
        var destinationB = $("#endc").val();

        if ($("#endd").val() == ''){
            var origin3 = '';
            var destinationC = '';
        }else{
            var origin3 = $("#endc").val();
            var destinationC = $("#endd").val();
        }

        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix(
        {
            origins: [origin1, origin2, origin3],
            destinations: [destinationA, destinationB, destinationC],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false,
        }, callback);
    };
    //CALLBACK API GOOGLE DISTANCE MATRIX
    function callback(response, status) {
        var distanciaAB;
        var distanciaBC;
        var distanciaCD;

        if (response.rows[0].elements[0].status == 'NOT_FOUND' || 
            response.rows[1].elements[1].status == 'NOT_FOUND'){
            alert("Preencha corretamente ao menos os campos Endereço A, B e C. \n\
                Sendo A sua origem, B seu destino e C seu retorno.");
            $('#enda, #endb, #endc, #endd').val('');
        }else{
            distanciaAB = (response.rows[0].elements[0].distance.value/1000);
            distanciaBC = (response.rows[1].elements[1].distance.value/1000);
        }

        if (response.destinationAddresses[2] == ''){
            distanciaCD = 0;
        }else{
            distanciaCD = (response.rows[2].elements[2].distance.value/1000);
        }
        totalkm = (parseFloat(distanciaAB) + parseFloat(distanciaBC) + parseFloat(distanciaCD)).toFixed(2);
        $("#totalkm").val(totalkm);
        calculaKm();
        calculaValor();
    }
    //////////////////////////////////////////////////////////////////////////
    // -------------- CHAMADAS E CALLBACKS API LEGAL ONE ------------------ //
    //////////////////////////////////////////////////////////////////////////
    
    //CHAMADA AJAX API LEGALONE
    function legalone_api_call(id) {
        $.ajax({
            type: "POST",
            url: './call_legalone_api',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            data: $("#pasta"+id).val(),
            beforeSend: function() {
                $('#loaderpasta'+id).css('visibility', 'visible');
            },
            complete: function() {
                $('#loaderpasta'+id).css('visibility', 'hidden');
            },
            success: function(response){
                legalone_api_response(response, id);
            }
        })
    };
    
    function legalone_api_response(response, id) {
        var obj = JSON.parse(response);
        $.each(obj, function(key,val){
            var options = '';
            $.each(val, function(key,val){
                options += 
                '<button type="button" class="linkpastas list-group-item" value="'+id+'">'+val.folder+'</button>';

                if(val.identifierNumber){
                    options += 
                    '<span class="'+val.folder+'-proc'+id+'" hidden>'+val.identifierNumber+'</span>';
                }else{
                    options += 
                    '<span class="'+val.folder+'-proc'+id+'" hidden>'+val.oldNumber+'</span>';
                }
                for (var i=0; i<val.participants.length; i++){
                    
                    if(val.participants[i].type == "Customer"){
                        options += 
                        '<span class="'+val.folder+'-customer'+id+'" hidden>'+val.participants[i].contactId+'</span>';
                    }else{
                        options += 
                        '<span id="otherparty" class="'+val.folder+'-otherparty'+id+'" hidden>'+val.participants[i].contactId+'</span>';
                    }
                }
            });
            $('#display'+id).append(options);
        });
    }
    
    function legalone_api_call_contacts(contactId, tipo, id) {
        $.ajax({
            type: "POST",
            url: './call_legalone_api_contacts',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            data: contactId,
            beforeSend: function() {
                $('#loadercliente'+id).css('visibility', 'visible');
                $('#loadercontrario'+id).css('visibility', 'visible');
            },
            complete: function() {
                $('#loadercliente'+id).css('visibility', 'hidden');
            },
            success: function(response) {
                legalone_api_contacts_response(response, tipo, contactId, id);
            } 
        })
    };
    
    function legalone_api_contacts_response(response, tipo, contactId, id) {
        var obj = JSON.parse(response);
        if(tipo == "Customer" && obj.name){
            $("#cliente"+id).val(obj.name);
        }
        else if(tipo == "OtherParty" && obj.name){
            $("#contrario"+id).val(obj.name);
            $('#loadercontrario'+id).css('visibility', 'hidden');
        }
        else {
            legalone_api_call_contacts_individuals(contactId, tipo, id);
        }
    }
    
    function legalone_api_call_contacts_individuals(contactId, tipo, id) {
        $.ajax({
            type: "POST",
            url: './call_legalone_api_contacts_individuals',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            data: contactId,
            beforeSend: function() {
                $('#loadercontrario'+id).css('visibility', 'visible');
            },
            complete: function() {
                $('#loadercontrario'+id).css('visibility', 'hidden');
            },
            success: function(response) {
                legalone_api_contacts_individuals_response(response, tipo, id);
            } 
        })
    };
    
    function legalone_api_contacts_individuals_response(response, tipo, id) {
        var obj = JSON.parse(response);
        if(tipo == "Customer"){
            $("#cliente"+id).val(obj.name);
        }
        else if(tipo == "OtherParty"){
            $("#contrario"+id).val(obj.name);
        }
    }
    
});
