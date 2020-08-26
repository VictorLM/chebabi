<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>SOLICITAÇÃO DE CORREIO - {{ $correio->id }}<</title>
    <link href="../public/assets/css/relatorio.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <style>
        * { font-size: 24px; }
        h1 { font-size: 48px; }
        @page { margin: 5px; }
        body { margin: 5px; }
    </style>
</head>
<body>
    
    <div class="container-fluid">

        <div class="logo">
            <img width="250px" height="50px" src="../public/assets/imagens/logo_md.png">
        </div>
        <div class="datetime">
            <small>{{ Carbon\Carbon::parse($correio->data)->format('d/m/Y') }}</small>
        </div>

        <br/><br/><br/>
        <h1>SOLICITAÇÃO DE CORREIO - {{ $correio->id }}</h1>
        <table>
            <tbody>
                <tr>
                    <td class="w50"><strong>SOLICITANTE: </strong>{{ Auth::user()->name }}</td>
                    <td class="w50"><strong>DATA: </strong>{{ Carbon\Carbon::parse($correio->data)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="w50"><strong>TIPO: </strong>{{ str_replace("_", " ", $correio->tipo) }}</td>
                    <td class="w50"><strong>REEMBOLSÁVEL: </strong>@if($correio->reembolsavel) SIM @else NÃO @endif</td>
                </tr>
                @if(!$correio->reembolsavel)
                    <tr>
                        <td colspan="2"><strong>MOTIVO: </strong>{{ $correio->motivo }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="w50"><strong>PASTA: </strong>{{ $correio->pasta }}</td>
                    <td class="w50"><strong>CLIENTE: </strong>{{ $correio->cliente }}</td>
                </tr>
                <tr>
                    <td class="w50"><strong>DESTINATÁRIO: </strong>{{ $correio->destinatario }}</td>
                    <td class="w50"><strong>AOS CUIDADOS DE: </strong>{{ $correio->ac }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>DESCRIÇÃO/AR: </strong>{{ $correio->descricao }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>ENDEREÇO: </strong>
                        {{ $correio->rua . ', ' . $correio->numero . ', ' . ($correio->complemento == null ? '' : ($correio->complemento. ', ')) . $correio->cidade . ' - ' . $correio->estado . ', CEP: ' . $correio->cep }}
                    </td>
                </tr>
            </tbody>
        </table>

        <hr/>

    </div>

</body>
</html>