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
                $("input[name=descricaodespesa"+i+"]").val("");
                $("input[name=despesapasta"+i+"]").val("");
                $("input[name=clientedespesa"+i+"]").val("");
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
                $("#cliente"+i).prop('readonly', false);
                //RESETA INPUTS
                $("input[name=pasta"+i+"]").val("");
                $("input[name=cliente"+i+"]").val("");
                $("input[name=contrario"+i+"]").val("");
                $("input[name=proc"+i+"]").val("");
                $("input[name=motivoviagem"+i+"]").val("");
                $("input[name=cliente-id"+i+"]").val("");
                $("#descricao_cliente"+i).text("Cliente "+i);
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
        clientesDespesas();
    });
    //MOSTRA OS INPUTS ADICIONAIS DE ENDEREÇO
    $('#add-endereco').click(function(){
        var next = false;
        var i = 4;
        while (!next){
            if($('.endereco'+i).css('display') == 'none'){
                $('.endereco'+i).css('display', 'block');
                //SETA REQUIRED TRUE
                $("input[name=end"+i+"]").prop('required',true);
                next = true;
            }
            i++;
            if(i>5){
                next = true;
            }
        }
    });
    //ESCONDE OS INPUTS ADICIONAIS DE ENDEREÇO
    $('#del-endereco').click(function(){
        var next = false;
        var i = 5;
        while (!next){
            if($('.endereco'+i).css('display') == 'block'){
                $('.endereco'+i).css('display', 'none');
                //RESETA INPUTS
                $("input[name=end"+i+"]").val("");
                //SETA REQUIRED FALSE
                $("input[name=end"+i+"]").prop('required',false);
                next = true;
            }
            i--;
            if(i<4){
                next = true;
            }
        }
        calculaDistancia();
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

    clientesDespesas = function(){
        $('.clientes-despesas').empty().append('<option value="TODOS">TODOS OS CLIENTES</option>');
        var next = false;
        var i = 1;
        var options = '';
        while (!next){
            if($('#cliente'+i).val() != ''){

                options += 
                '<option value="'+$("#cliente"+i).val().toUpperCase()+'">'+$("#cliente"+i).val().toUpperCase()+'</option>';
            }
            i++;
            if(i>3){
                next = true;
            }
        }
        $('.clientes-despesas').append(options);
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

    $(".cliente").on('keyup change', function (){
        clientesDespesas();
    });
    
    $(".calcend").change(function(){
        if($('#tipo_viagem').val() == '1'){
            setTimeout(calculaDistancia,100);
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
            $("#display-cliente"+id).empty();
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

    //CHECAGEM GERAL ANTES DE SUBMETER O FORMULÁRIO 1
    $("#enviar").click(function(){
        $('.required').each(function(){
            if($(this).val() == ""){
                alert("Preencha todos os campos obrigatórios corretamente!");
                $("#infos-gerais").show(300);
                $('#control-infos-gerais').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $(".infos-clientes").show(300);
                $('#control-infos-clientes').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $(".infos-viagem").show(300);
                $('#control-infos-viagem').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $(".infos-despesas").show(300);
                $('#control-infos-despesas').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $(".infos-valores").show(300);
                $('#control-infos-valores').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $("#infos-obs").show(300);
                $('#control-infos-obs').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $("#infos-comprovantes").show(300);
                $('#control-infos-comprovantes').addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $(this).focus();
                return false;
            }
        });
    });
    //CHECAGEM GERAL ANTES DE SUBMETER O FORMULÁRIO 2
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
        if(!$('#cliente1').attr('readonly')){
            $('#modalCliente').modal('show');
            $(".infos-clientes").show(300);
            $('#control-infos-clientes').addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $('#cliente1').focus();
            return false;
        }
        $('#loaderModal').modal('show');
        $('#loaderModal').modal({backdrop: 'static', keyboard: false});
    });

    $(".cliente").keyup(function(){
        var cliente = $(this).val();
        var id = $(this).attr('id');
        id = id.replace(/[^\d]+/, '');
        $("#display-cliente"+id).empty();
        if($(this).val() != "" && $("#cliente-id"+id).val() == ""){
            clientes(cliente, id);
        }
    });

    $(document).on('click', "button.link-cliente", function() {
        var id = $(this).attr('id');
        id = id.replace(/[^\d]+/, '');
        if($(this).val() != ""){
            $("#cliente-id"+id).val($(this).val());
            $("#cliente"+id).val($(this).text());
            $("#cliente"+id).prop('readonly', true);
            $("#descricao_cliente"+id).text($("#cliente"+id).val().toUpperCase());
        }else{
            $("#cliente"+id).prop('readonly', true);
            $("#descricao_cliente"+id).text($("#cliente"+id).val().toUpperCase());
        }
        $("#display-cliente"+id).empty();
        clientesDespesas();
    });

    //CONTROLES MINIMIZAR E MAXIMIZAR/////////////////////////////////////////

    $(".esconder").hide();

    $("#control-infos-gerais").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $("#infos-gerais").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $("#infos-gerais").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

    $("#control-infos-clientes").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $(".infos-clientes").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $(".infos-clientes").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

    $("#control-infos-viagem").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $(".infos-viagem").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $(".infos-viagem").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

    $("#control-infos-despesas").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $(".infos-despesas").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $(".infos-despesas").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

    $("#control-infos-valores").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $(".infos-valores").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $(".infos-valores").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

    $("#control-infos-obs").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $("#infos-obs").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $("#infos-obs").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });

    $("#control-infos-comprovantes").click( function (){
        if($(this).hasClass("glyphicon-minus")){
            $("#infos-comprovantes").hide(300);
            $(this).addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }else{
            $("#infos-comprovantes").show(300);
            $(this).addClass('glyphicon-minus').removeClass('glyphicon-plus');
        }
    });
    ////SHOW E HIDE DOS BLOCOS CONFORME O PREENCHIMENTO
    $('#reembolsavel').change(function(){
        if($('#tipo_viagem').val() == 0){
            $(".infos-clientes").show(300);
            $("#control-infos-clientes").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $("#infos-gerais").hide(300);
            $("#control-infos-gerais").addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }
     });

     $('#carro').change(function(){
        if($('#carro').val() == 'Particular'){
            $(".infos-clientes").show(300);
            $("#control-infos-clientes").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $("#infos-gerais").hide(300);
            $("#control-infos-gerais").addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }
     });

     $('#pedagio').change(function(){
        if($('#pedagio').val() != ''){
            $(".infos-clientes").show(300);
            $("#control-infos-clientes").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $("#infos-gerais").hide(300);
            $("#control-infos-gerais").addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }
     });

     $('#finaliza-clientes').click(function(){
        if($('#pasta1').val() == '' || $('#cliente1').val() == '' || $('#contrario1').val() == '' || $('#proc1').val() == ''){
            alert("Preencha ao menos os dados de um clientes por completo!");
        }else if(!$('#cliente1').attr('readonly')){
            $('#modalCliente').modal('show');
        }else{
            $(".infos-viagem").show(300);
            $("#control-infos-viagem").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $(".infos-clientes").hide(300);
            $("#control-infos-clientes").addClass('glyphicon-plus').removeClass('glyphicon-minus');
        }
        clientesDespesas();
     });

     $('#finaliza-despesas').click(function(){
        $(".infos-valores").show(300);
        $("#control-infos-valores").addClass('glyphicon-minus').removeClass('glyphicon-plus');
        $(".infos-despesas").hide(300);
        $("#control-infos-despesas").addClass('glyphicon-plus').removeClass('glyphicon-minus');
    });

     $('#finaliza-viagem').click(function(){
        if($('#tipo_viagem').val() == '1'){
            if($('#enda').val() != '' && $('#endb').val() != '' && $('#endc').val() != '' && $('#totalkm').val() != '' && $('#data').val() != '' && $('#motivoviagem1').val() != ''){
                if($('#motivoviagem2').prop('required') && !$('#motivoviagem3').prop('required')){
                    if($('#motivoviagem2').val() == ''){
                        alert("Preencha todos os campos obrigatórios!");
                    }else{
                        $(".infos-despesas").show(300);
                        $("#control-infos-despesas").addClass('glyphicon-minus').removeClass('glyphicon-plus');
                        $(".infos-viagem").hide(300);
                        $("#control-infos-viagem").addClass('glyphicon-plus').removeClass('glyphicon-minus');
                    }
                }else if($('#motivoviagem3').prop('required')){
                    if($('#motivoviagem2').val() == '' || $('#motivoviagem3').val() == ''){
                        alert("Preencha todos os campos obrigatórios!");
                    }else{
                        $(".infos-despesas").show(300);
                        $("#control-infos-despesas").addClass('glyphicon-minus').removeClass('glyphicon-plus');
                        $(".infos-viagem").hide(300);
                        $("#control-infos-viagem").addClass('glyphicon-plus').removeClass('glyphicon-minus');
                    }
                }else{
                    $(".infos-despesas").show(300);
                    $("#control-infos-despesas").addClass('glyphicon-minus').removeClass('glyphicon-plus');
                    $(".infos-viagem").hide(300);
                    $("#control-infos-viagem").addClass('glyphicon-plus').removeClass('glyphicon-minus');
                }
            }else{
                alert("Preencha todos os campos obrigatórios!");
            }
        }else if($('#tipo_viagem').val() == '0'){
            if($('#data').val() == '' || $('#motivoviagem1').val() == ''){
                alert("Preencha todos os campos obrigatórios!");
            }else{
                $(".infos-despesas").show(300);
                $("#control-infos-despesas").addClass('glyphicon-minus').removeClass('glyphicon-plus');
                $(".infos-viagem").hide(300);
                $("#control-infos-viagem").addClass('glyphicon-plus').removeClass('glyphicon-minus');
            }
        }else{
            alert("Selecione o tipo de viagem!");
            $("#infos-gerais").show(300);
            $("#control-infos-gerais").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $("#tipo_viagem").focus();
        }
        calculaDistancia();
        clientesDespesas();
     });

     $('#finaliza-valores').click(function(){
        if($('#caucao').val() == ''){
            alert("Preencha o valor de caução, mesmo se for zero!");
        }else{
            $("#infos-obs").show(300);
            $("#control-infos-obs").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $("#infos-comprovantes").show(300);
            $("#control-infos-comprovantes").addClass('glyphicon-minus').removeClass('glyphicon-plus');
            $(".infos-valores").hide(300);
            $("#control-infos-valores").addClass('glyphicon-plus').removeClass('glyphicon-minus');
            $("#enviar").show(300);
        }
    });

    //////////////////////////////////////////////////////////////////////////
    // -------- CHAMADAS E CALLBACKS APIS GOOGLE DISTANCE MATRIX  --------- //
    //////////////////////////////////////////////////////////////////////////
    
    //CHAMADA API GOOGLE DISTANCE MATRIX
    calculaDistancia = function(){

        var enda = $("#enda").val();
        var endb = $("#endb").val();
        var endc = $("#endc").val();
        var endd = $("#endd").val();
        var ende = $("#ende").val();

        var service = new google.maps.DistanceMatrixService();

        if(enda != '' && endb != '' && endc != '' && endd != '' && ende != ''){

            service.getDistanceMatrix(
            {
                origins: [enda, endb, endc, endd],
                destinations: [endb, endc, endd, ende],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false,
            }, callback);

        }else if(enda != '' && endb != '' && endc != '' && endd != ''){

            service.getDistanceMatrix(
            {
                origins: [enda, endb, endc],
                destinations: [endb, endc, endd],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false,
            }, callback);

        }else if(enda != '' && endb != '' && endc != ''){
            
            service.getDistanceMatrix(
            {
                origins: [enda, endb],
                destinations: [endb, endc],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false,
            }, callback);

        }

    };
    //CALLBACK API GOOGLE DISTANCE MATRIX
    function callback(response, status) {
        var distanciaAB = 0;
        var distanciaBC = 0;
        var distanciaCD = 0;
        var distanciaDE = 0;
        var error = false;

        if (status !== 'OK'){
            alert('Erro: ' + status + ' - Tente novamente mais tarde.');
        }else{
            //console.log(response['rows'].length);
            //console.log(response);

            if (response['rows'].length == 2){

                if (response.rows[0].elements[0].status == 'NOT_FOUND' || 
                    response.rows[1].elements[1].status == 'NOT_FOUND'){
                    alert("Preencha corretamente ao menos os campos Endereço A, B e C. \n\
                        Sendo A sua origem, B seu destino e C seu retorno.");
                    $('#enda, #endb, #endc, #endd, #ende').val('');
                }else{
                    distanciaAB = (response.rows[0].elements[0].distance.value/1000);
                    distanciaBC = (response.rows[1].elements[1].distance.value/1000);
                }

            }else if (response['rows'].length == 3){

                if (response.rows[0].elements[0].status == 'NOT_FOUND' || 
                    response.rows[1].elements[1].status == 'NOT_FOUND' || 
                    response.rows[2].elements[2].status == 'NOT_FOUND'){
                    alert("Preencha corretamente ao menos os campos Endereço A, B e C. \n\
                        Sendo A sua origem, B seu destino e C seu retorno.");
                    $('#enda, #endb, #endc, #endd, #ende').val('');
                }else{
                    distanciaAB = (response.rows[0].elements[0].distance.value/1000);
                    distanciaBC = (response.rows[1].elements[1].distance.value/1000);
                    distanciaCD = (response.rows[2].elements[2].distance.value/1000);
                }

            }else if (response['rows'].length == 4){

                if (response.rows[0].elements[0].status == 'NOT_FOUND' || 
                    response.rows[1].elements[1].status == 'NOT_FOUND' || 
                    response.rows[2].elements[2].status == 'NOT_FOUND' ||
                    response.rows[3].elements[3].status == 'NOT_FOUND'){
                    alert("Preencha corretamente ao menos os campos Endereço A, B e C. \n\
                        Sendo A sua origem, B seu destino e C seu retorno.");
                    $('#enda, #endb, #endc, #endd, #ende').val('');
                }else{
                    distanciaAB = (response.rows[0].elements[0].distance.value/1000);
                    distanciaBC = (response.rows[1].elements[1].distance.value/1000);
                    distanciaCD = (response.rows[2].elements[2].distance.value/1000);
                    distanciaDE = (response.rows[3].elements[3].distance.value/1000);
                }

            }

            totalkm = (parseFloat(distanciaAB) + parseFloat(distanciaBC) + parseFloat(distanciaCD) + parseFloat(distanciaDE)).toFixed(2);
            $("#totalkm").val(totalkm);
            calculaKm();
            calculaValor();

        }

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
            clientes(obj.name, id);
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
            clientes(obj.name, id);
        }
        else if(tipo == "OtherParty"){
            $("#contrario"+id).val(obj.name);
        }
    }
    
    //////////////////////////////////////////////////////////////////////////
    // ---------------- CHAMADA E CALLBACK CLIENTES KM -------------------- //
    //////////////////////////////////////////////////////////////////////////

    //CHAMADA AJAX CLIENTES KM
    function clientes(cliente, id) {
        $("#display-cliente"+id).empty();
        $.ajax({
            type: "POST",
            url: './clientes_ajax',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "html",
            data: cliente,
            beforeSend: function() {
                $('#loader-cliente').css('visibility', 'visible');
            },
            complete: function() {
                $('#loader-cliente').css('visibility', 'hidden');
            },
            success: function(response) {
                clientes_response(response, id);
            }
        })
    };
    
    function clientes_response(response, id) {
        $("#display-cliente"+id).empty();
        var obj = JSON.parse(response);
        var options = '';
        if(obj.length>0){
            $.each(obj, function(key,val){
                options += 
                '<button type="button" class="link-cliente list-group-item" id="link-cliente'+id+'" value="'+val.id+'">'+val.cliente+'</button>';
            });
            options += 
            '<button type="button" class="link-cliente list-group-item" id="link-cliente'+id+'" value="">OUTRO</button>';
        }else{
            options += 
            '<button type="button" class="link-cliente list-group-item" id="link-cliente'+id+'" value="">OUTRO</button>';
        }
        if($("#cliente-id"+id).val() == ""){
            $('#display-cliente'+id).append(options);
        }
    }


    ////////MULTI SELECT
    $('.despesas-cliente').multiselect({
    disableIfEmpty: true,
    buttonText: function(options, select) {
        if (options.length === 0) {
            return 'Nenhuma cliente selecionado...';
        }
        else if (options.length > 3) {
            return 'Mais de 3 clientes selecionados!';
        }
         else {
             var labels = [];
             options.each(function() {
                 if ($(this).attr('label') !== undefined) {
                     labels.push($(this).attr('label'));
                 }
                 else {
                     labels.push($(this).html());
                 }
             });
             return labels.join(', ') + '';
         }
    }
});

$(".cliente").change(function(){

    var data = [
        {label: $(this).val(), value: $(this).val()}
    ];

    $(".despesas-cliente").multiselect('dataprovider', data);
});
////////FIM MULTI SELECT





});

//Google Place Autocomplete Address Form
var placeSearch, autocomplete;

function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('enda')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('endb')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('endc')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('endd')),
        {types: ['geocode']});
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('ende')),
        {types: ['geocode']});
    autocomplete.addListener(fillInAddress);
}

function fillInAddress() {
var place = autocomplete.getPlace();
}
//Fim - Google Place Autocomplete Address Form