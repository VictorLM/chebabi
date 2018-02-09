<?php

if($request->input('reembolsavel')){
    $reembolsavel = "Sim";
}else if (empty($request->input('reembolsavel'))){
    $reembolsavel = "";
}else{
    $reembolsavel = "Não";
}

if(!empty($request->input('pasta2')) && !empty($request->input('cliente2')) &&
    empty($request->input('pasta3')) && empty($request->input('cliente3'))){
    //dd("TESTE CLIENTE 2 NOT NULL");
    
    $html1 = '
    <html>
        <body>
            <div id="principal">
                <h2>RELATÓRIO DE VIAGEM</h2>
                <br>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Responsável:</b> ' .Auth::user()->name. '</td>
                        <td><b>Data:</b> ' .date('d/m/Y'). '</td>
                    </tr>
                    <tr>
                        <td><b>Tipo de viagem:</b> ' .$request->input('tipo_viagem'). '</td>
                        <td><b>Reembolsável:</b> ' .$reembolsavel. '</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Protocolo:</b> ' .$identificador. '</td>
                    </tr>
                </table>
                <br>
                <h3>INFORMAÇÕES DO CLIENTE</h3>
                <h5>CLIENTE 1</h5>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('cliente1'). '</td>
                        <td><b>Parte contrária:</b> ' .$request->input('contrario1'). '</td>
                    </tr>
                    <tr>
                        <td><b>Número da Pasta:</b> ' .$request->input('pasta1'). '</td>
                        <td><b>Número da Processo:</b> ' .$request->input('proc1'). '</td>
                    </tr>
                </table>
                <h5>CLIENTE 2</h5>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('cliente2'). '</td>
                        <td><b>Parte contrária:</b> ' .$request->input('contrario2'). '</td>
                    </tr>
                    <tr>
                        <td><b>Número da Pasta:</b> ' .$request->input('pasta2'). '</td>
                        <td><b>Número da Processo:</b> ' .$request->input('proc2'). '</td>
                    </tr>
                </table>
                <br>
                <h3>INFORMAÇÕES DA VIAGEM</h3>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição viagem 1:</b> ' .$request->input('motivoviagem1'). '</td>
                        <td><b>Descrição viagem 2:</b> ' .$request->input('motivoviagem2'). '</td>
                    </tr>
                     <tr>
                        <td colspan="2"><b>Data da viagem:</b> ' .Carbon\Carbon::parse($request->input('data'))->format('d/m/Y'). '</td>
                    </tr>
                </table>
                <br>
                <h3>DESPESAS GERAIS</h3>
                <h5>DESPESAS GERAIS 1</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa1'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta1'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa1'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais1'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 2</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa2'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta2'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa2'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais2'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 3</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa3'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta3'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa3'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais3'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 4</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa4'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta4'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa4'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais4'). '</td>
                    </tr>
                </table>
                <br>
                <h3>VALORES</h3>
                <table  id="clientes2" border="1">
                    <tr>
                        <td colspan="2"><b>Valor caução:</b> R$ ' .$request->input('caucao'). '</td>
                    </tr>
                    <tr>
                        <td><b>Valor despesas:</b> R$ ' .$request->input('totalgastos'). '</td>
                        <td><b>Valor a ser devolvido:</b> R$ ' .$request->input('aserdevolvido'). '</td>
                    </tr>
                </table>
                <br>
                <h3>OBSERVAÇÕES</h3>
                <table border="1">
                    <tr>
                        <td colspan="2"><b>Observações:</b> ' .$request->input('observacoes'). '</td>
                    </tr>
                </table>
                <br>
                <p>Eu, <b>' .Auth::user()->name. '</b>, declaro para os devidos fins, 
                ter recebido o valor descrito no campo "VALOR A SER DEVOLDIDO".</p>

                <p>Campinas, ' .strftime("%d de %B de %Y").'.</p>

            </div>
        </body>
    </html>';
    
}else if (!empty($request->input('pasta2')) && !empty($request->input('cliente2')) &&
          !empty($request->input('pasta3')) && !empty($request->input('cliente3'))){
    //dd("TESTE CLIENTE 3 NOT NULL");
    
    $html1 = '
    <html>
        <body>
            <div id="principal">
                <h2>RELATÓRIO DE VIAGEM</h2>
                <br>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Responsável:</b> ' .Auth::user()->name. '</td>
                        <td><b>Data:</b> ' .date('d/m/Y'). '</td>
                    </tr>
                    <tr>
                        <td><b>Tipo de viagem:</b> ' .$request->input('tipo_viagem'). '</td>
                        <td><b>Reembolsável:</b> ' .$reembolsavel. '</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Protocolo:</b> ' .$identificador. '</td>
                    </tr>
                </table>
                <br>
                <h3>INFORMAÇÕES DO CLIENTE</h3>
                <h5>CLIENTE 1</h5>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('cliente1'). '</td>
                        <td><b>Parte contrária:</b> ' .$request->input('contrario1'). '</td>
                    </tr>
                    <tr>
                        <td><b>Número da Pasta:</b> ' .$request->input('pasta1'). '</td>
                        <td><b>Número da Processo:</b> ' .$request->input('proc1'). '</td>
                    </tr>
                </table>
                <h5>CLIENTE 2</h5>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('cliente2'). '</td>
                        <td><b>Parte contrária:</b> ' .$request->input('contrario2'). '</td>
                    </tr>
                    <tr>
                        <td><b>Número da Pasta:</b> ' .$request->input('pasta2'). '</td>
                        <td><b>Número da Processo:</b> ' .$request->input('proc2'). '</td>
                    </tr>
                </table>
                <h5>CLIENTE 3</h5>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('cliente3'). '</td>
                        <td><b>Parte contrária:</b> ' .$request->input('contrario3'). '</td>
                    </tr>
                    <tr>
                        <td><b>Número da Pasta:</b> ' .$request->input('pasta3'). '</td>
                        <td><b>Número da Processo:</b> ' .$request->input('proc3'). '</td>
                    </tr>
                </table>
                <br>
                <h3>INFORMAÇÕES DA VIAGEM</h3>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição viagem 1:</b> ' .$request->input('motivoviagem1'). '</td>
                        <td><b>Descrição viagem 2:</b> ' .$request->input('motivoviagem2'). '</td>
                    </tr>
                     <tr>
                        <td><b>Descrição viagem 3:</b> ' .$request->input('motivoviagem3'). '</td>
                        <td><b>Data da viagem:</b> ' .Carbon\Carbon::parse($request->input('data'))->format('d/m/Y'). '</td>
                    </tr>
                </table>
                <br>
                <h3>DESPESAS GERAIS</h3>
                <h5>DESPESAS GERAIS 1</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa1'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta1'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa1'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais1'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 2</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa2'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta2'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa2'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais2'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 3</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa3'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta3'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa3'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais3'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 4</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa4'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta4'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa4'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais4'). '</td>
                    </tr>
                </table>
                <br>
                <h3>VALORES</h3>
                <table  id="clientes2" border="1">
                    <tr>
                        <td colspan="2"><b>Valor caução:</b> R$ ' .$request->input('caucao'). '</td>
                    </tr>
                    <tr>
                        <td><b>Valor despesas:</b> R$ ' .$request->input('totalgastos'). '</td>
                        <td><b>Valor a ser devolvido:</b> R$ ' .$request->input('aserdevolvido'). '</td>
                    </tr>
                </table>
                <br>
                <h3>OBSERVAÇÕES</h3>
                <table border="1">
                    <tr>
                        <td colspan="2"><b>Observações:</b> ' .$request->input('observacoes'). '</td>
                    </tr>
                </table>
                <br>
                <p>Eu, <b>' .Auth::user()->name. '</b>, declaro para os devidos fins, 
                ter recebido o valor descrito no campo "VALOR A SER DEVOLDIDO".</p>

                <p>Campinas, ' .strftime("%d de %B de %Y").'.</p>

            </div>
        </body>
    </html>';
    
}else{
    //dd("TESTE CLIENTE 1 NOT NULL");
    
    $html1 = '
    <html>
        <body>
            <div id="principal">
                <h2>RELATÓRIO DE VIAGEM</h2>
                <br>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Responsável:</b> ' .Auth::user()->name. '</td>
                        <td><b>Data:</b> ' .date('d/m/Y'). '</td>
                    </tr>
                    <tr>
                        <td><b>Tipo de viagem:</b> ' .$request->input('tipo_viagem'). '</td>
                        <td><b>Reembolsável:</b> ' .$reembolsavel. '</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Protocolo:</b> ' .$identificador. '</td>
                    </tr>
                </table>
                <br>
                <h3>INFORMAÇÕES DO CLIENTE</h3>
                <table id="clientes2" border="1">
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('cliente1'). '</td>
                        <td><b>Parte contrária:</b> ' .$request->input('contrario1'). '</td>
                    </tr>
                    <tr>
                        <td><b>Número da Pasta:</b> ' .$request->input('pasta1'). '</td>
                        <td><b>Número da Processo:</b> ' .$request->input('proc1'). '</td>
                    </tr>
                </table>
                <br>
                <h3>INFORMAÇÕES DA VIAGEM</h3>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição viagem 1:</b> ' .$request->input('motivoviagem1'). '</td>
                        <td><b>Data da viagem:</b> ' .Carbon\Carbon::parse($request->input('data'))->format('d/m/Y'). '</td>
                    </tr>
                </table>
                <br>
                <h3>DESPESAS GERAIS</h3>
                <h5>DESPESAS GERAIS 1</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa1'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta1'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa1'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais1'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 2</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa2'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta2'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa2'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais2'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 3</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa3'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta3'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa3'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais3'). '</td>
                    </tr>
                </table>
                <h5>DESPESAS GERAIS 4</h5>
                <table  id="clientes2" border="1">
                    <tr>
                        <td><b>Descrição:</b> ' .$request->input('descricaodespesa4'). '</td>
                        <td><b>Número da Pasta:</b> ' .$request->input('despesapasta4'). '</td>
                    </tr>
                    <tr>
                        <td><b>Cliente:</b> ' .$request->input('clientedespesa4'). '</td>
                        <td><b>Custo:</b> ' .$request->input('despesasgerais4'). '</td>
                    </tr>
                </table>
                <br>
                <h3>VALORES</h3>
                <table  id="clientes2" border="1">
                    <tr>
                        <td colspan="2"><b>Valor caução:</b> R$ ' .$request->input('caucao'). '</td>
                    </tr>
                    <tr>
                        <td><b>Valor despesas:</b> R$ ' .$request->input('totalgastos'). '</td>
                        <td><b>Valor a ser devolvido:</b> R$ ' .$request->input('aserdevolvido'). '</td>
                    </tr>
                </table>
                <br>
                <h3>OBSERVAÇÕES</h3>
                <table border="1">
                    <tr>
                        <td colspan="2"><b>Observações:</b> ' .$request->input('observacoes'). '</td>
                    </tr>
                </table>
                <br>
                <p>Eu, <b>' .Auth::user()->name. '</b>, declaro para os devidos fins, 
                ter recebido o valor descrito no campo "VALOR A SER DEVOLDIDO".</p>

                <p>Campinas, ' .strftime("%d de %B de %Y").'.</p>

            </div>
        </body>
    </html>';
    
}
