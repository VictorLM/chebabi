<?php

$cliente1 = mb_strtoupper($request->input('cliente1'));
$cliente2 = mb_strtoupper($request->input('cliente2'));
$cliente3 = mb_strtoupper($request->input('cliente3'));
$totalcliente = $request->input('totalgastos');
$footer =  '<b>Advogado:</b> ' .Auth::user()->name.'.';

$html1 = '
<html>
    <body>
        <div id="principal">
            <h2>RELATÓRIO DE VIAGEM - CLIENTE '.$cliente1.'</h2>
            <br>
            <table id="clientes1" border="1">
                <tr>
                    <td><b><i>PARTE CONTRÁRIA</i></b></td>
                    <td><b><i>PROCESSO</i></b></td>
                    <td><b><i>PASTA</i></b></td>
                    <td><b><i>DATA</i></b></td>
                    <td><b><i>VALOR</i></b></td>
                    <td><b><i>MOTIVO</i></b></td>
                </tr>
                <tr>
                    <td>'.$request->input('contrario1').'</td>
                    <td>'.$request->input('proc1').'</td>
                    <td>'.$request->input('pasta1').'</td>
                    <td>'.Carbon\Carbon::parse($request->input('data'))->format('d/m/Y').'</td>
                    <td>R$ '.$totalcliente.'</td>
                    <td>'.$request->input('motivoviagem1').'</td>
                </tr>
            </table>
            <br>
            <h3>DESPESAS GERAIS</h3>
            <table id="clientes2" border="1">
                <tr>
                    <th><b><i>DESCRIÇÃO</i></b></td>
                    <th><b><i>CUSTO</i></b></td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa1').'</td>
                    <td>R$ '.$request->input('despesasgerais1').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa2').'</td>
                    <td>R$ '.$request->input('despesasgerais2').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa2').'</td>
                    <td>R$ '.$request->input('despesasgerais3').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa4').'</td>
                    <td>R$ '.$request->input('despesasgerais4').'</td>
                </tr>
            </table>
        </div>
    </body>
</html>';

$html2 = '
<html>
    <body>
        <div id="principal">
            <h2>RELATÓRIO DE VIAGEM - CLIENTE '.$cliente2.'</h2>
            <br>
            <table id="clientes1" border="1">
                <tr>
                    <td><b><i>PARTE CONTRÁRIA</i></b></td>
                    <td><b><i>PROCESSO</i></b></td>
                    <td><b><i>PASTA</i></b></td>
                    <td><b><i>DATA</i></b></td>
                    <td><b><i>VALOR</i></b></td>
                    <td><b><i>MOTIVO</i></b></td>
                </tr>
                <tr>
                    <td>'.$request->input('contrario2').'</td>
                    <td>'.$request->input('proc2').'</td>
                    <td>'.$request->input('pasta2').'</td>
                    <td>'.Carbon\Carbon::parse($request->input('data'))->format('d/m/Y').'</td>
                    <td>R$ '.$totalcliente.'</td>
                    <td>'.$request->input('motivoviagem2').'</td>
                </tr>
            </table>
            <br>
            <h3>DESPESAS GERAIS</h3>
            <table id="clientes2" border="1">
                <tr>
                    <th><b><i>DESCRIÇÃO</i></b></td>
                    <th><b><i>CUSTO</i></b></td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa1').'</td>
                    <td>R$ '.$request->input('despesasgerais1').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa2').'</td>
                    <td>R$ '.$request->input('despesasgerais2').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa2').'</td>
                    <td>R$ '.$request->input('despesasgerais3').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa4').'</td>
                    <td>R$ '.$request->input('despesasgerais4').'</td>
                </tr>
            </table>
        </div>
    </body>
</html>';

$html3 = '
<html>
    <body>
        <div id="principal">
            <h2>RELATÓRIO DE VIAGEM - CLIENTE '.$cliente3.'</h2>
            <br>
            <table id="clientes1" border="1">
                <tr>
                    <td><b><i>PARTE CONTRÁRIA</i></b></td>
                    <td><b><i>PROCESSO</i></b></td>
                    <td><b><i>PASTA</i></b></td>
                    <td><b><i>DATA</i></b></td>
                    <td><b><i>VALOR</i></b></td>
                    <td><b><i>MOTIVO</i></b></td>
                </tr>
                <tr>
                    <td>'.$request->input('contrario3').'</td>
                    <td>'.$request->input('proc3').'</td>
                    <td>'.$request->input('pasta3').'</td>
                    <td>'.Carbon\Carbon::parse($request->input('data'))->format('d/m/Y').'</td>
                    <td>R$ '.$totalcliente.'</td>
                    <td>'.$request->input('motivoviagem3').'</td>
                </tr>
            </table>
            <br>
            <h3>DESPESAS GERAIS</h3>
            <table id="clientes2" border="1">
                <tr>
                    <th><b><i>DESCRIÇÃO</i></b></td>
                    <th><b><i>CUSTO</i></b></td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa1').'</td>
                    <td>R$ '.$request->input('despesasgerais1').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa2').'</td>
                    <td>R$ '.$request->input('despesasgerais2').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa2').'</td>
                    <td>R$ '.$request->input('despesasgerais3').'</td>
                </tr>
                <tr>
                    <td>'.$request->input('descricaodespesa4').'</td>
                    <td>R$ '.$request->input('despesasgerais4').'</td>
                </tr>
            </table>
        </div>
    </body>
</html>';