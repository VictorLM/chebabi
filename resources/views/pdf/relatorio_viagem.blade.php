@php
    $total_despesas = 0;
    if($relatorio->veiculo == "ESCRITÓRIO"){
        $valor_km = 0;
    }elseif($relatorio->veiculo == "PARTICULAR"){
        $valor_km = round($relatorio->totalkm*0.8, 2);
    }else{
        $valor_km = 0;
    }

    $mes = ucfirst(strftime("%B"));
    switch($mes){
        case "January": $mes = "Janeiro"; break;
        case "February": $mes = "Fevereiro"; break;
        case "March": $mes = "Março"; break;
        case "April": $mes = "Abril"; break;
        case "May": $mes = "Maio"; break;
        case "June": $mes = "Junho"; break;
        case "July": $mes = "Julho"; break;
        case "August": $mes = "Agosto"; break;
        case "September": $mes = "Setembro"; break;
        case "October": $mes = "Outubro"; break;
        case "November": $mes = "Novembro"; break;
        case "December": $mes = "Dezembro"; break;
        default: $mes = "Mês"; break;
    }
    $dataextenso = strftime("%d de ".$mes." de %Y");

@endphp
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de viagem - {{$relatorio->identificador ?? null}}</title>
    <link href="../public/assets/css/relatorio.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <style>
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
            <small>{{Carbon\Carbon::parse($relatorio->created_at)->format('d/m/Y H:i') ?? null}}</small>
        </div>

        <br/><br/><br/>
        <h1>RELATÓRIO DE VIAGEM - {{$relatorio->identificador ?? null}}</h1>
        <table>
            <tbody>
                <tr>
                    <td class="w50"><strong>RESPONSÁVEL: </strong>{{mb_strtoupper(Auth::user()->name, 'UTF-8') ?? null}}</td>
                    <td class="w50"><strong>TIPO DE VIAGEM: </strong>@if($relatorio->kilometragem) COM QUILOMETRAGEM @else SEM QUILOMETRAGEM @endif</td>
                </tr>
                <tr>
                    <td class="w50"><strong>VEÍCULO: </strong>{{$relatorio->veiculo ?? null}}</td>
                    <td class="w50"><strong>REEMBOLSÁVEL: </strong>@if($relatorio->reembolsavel) SIM @else NÃO @endif</td>
                </tr>
            </tbody>
        </table>

        <hr/>
        <h3>
            @if(count(unserialize($relatorio->clientes))>1) INFORMAÇÕES DOS CLIENTES @else INFORMAÇÕES DO CLIENTE @endif
        </h3>
        @foreach(unserialize($relatorio->clientes) as $cliente)
            @if(count(unserialize($relatorio->clientes))>1) <h5>CLIENTE {{$loop->iteration}}</h5> @endif
            <table style="margin:0;padding:0;">
                <tr>
                    <td class="w50" style="border-bottom:1px solid white;"><strong>CLIENTE: </strong>{{$cliente['CLIENTE'] ?? null}}</td>
                    <td class="w50" style="border-bottom:1px solid white;"><strong>PARTE CONTRÁRIA: </strong>{{$cliente['CONTRARIO'] ?? null}}</td>
                </tr>
            </table>
            <table style="margin:0;padding:0;">
                <tr>
                    <td class="w333"><strong>PASTA: </strong>{{$cliente['PASTA'] ?? null}}</td>
                    <td class="w333"><strong>PROCESSO: </strong>{{$cliente['PROCESSO'] ?? null}}</td>
                    <td class="w333"><strong>MOTIVO VIAGEM: </strong>{{$cliente['DESCRICAO'] ?? null}}</td>
                </tr>
            </table>
        @endforeach

        <hr/>
        <h3>INFORMAÇÕES DA VIAGEM</h3>
            @if(count(unserialize($relatorio->enderecos))>0)  

                @if(count(unserialize($relatorio->enderecos))==3)
                <table style="margin:0;padding:0;">
                    <tr>
                        <th class="w20 center"><strong>PARTIDA: </strong></th>
                        <th class="w20 center"><strong>DESTINO: </strong></th>
                        <th class="w20 center"><strong>RETORNO: </strong></td>
                    </tr>
                    <tr>
                        <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[0] ?? null}}</td>
                        <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[1] ?? null}}</td>
                        <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[2] ?? null}}</td>
                    </tr>
                </table>
                @elseif(count(unserialize($relatorio->enderecos))==4)
                    <table style="margin:0;padding:0;">
                        <tr>
                            <th class="w20 center"><strong>PARTIDA: </strong></th>
                            <th class="w20 center"><strong>DESTINO 1: </strong></th>
                            <th class="w20 center"><strong>DESTINO 2: </strong></th>
                            <th class="w20 center"><strong>RETORNO: </strong></td>
                        </tr>
                        <tr>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[0] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[1] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[2] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[3] ?? null}}</td>
                        </tr>
                    </table>
                @elseif(count(unserialize($relatorio->enderecos))==5)
                    <table style="margin:0;padding:0;">
                        <tr>
                            <th class="w20 center"><strong>PARTIDA: </strong></th>
                            <th class="w20 center"><strong>DESTINO 1: </strong></th>
                            <th class="w20 center"><strong>DESTINO 2: </strong></th>
                            <th class="w20 center"><strong>DESTINO 3: </strong></td>
                            <th class="w20 center"><strong>RETORNO: </strong></td>
                        </tr>
                        <tr>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[0] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[1] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[2] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[3] ?? null}}</td>
                            <td class="w20" style="border-bottom:1px solid white;">{{unserialize($relatorio->enderecos)[4] ?? null}}</td>
                        </tr>
                    </table>
                @endif

            @endif
        <table style="margin:0;padding:0;">
            <tr>
                <td class="w333"><strong>DATA DA VIAGEM: </strong>{{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y') ?? null}}</td>
                <td class="w20"><strong>PEDÁGIO: </strong>@if($relatorio->pedagio) SIM @endif</td>
                <td class="w20"><strong>TOTAL KM: </strong>{{$relatorio->totalkm ?? null}}</td>
                <td class="w333"><strong>VALOR KM (R$ 0,80 / KM): </strong>R$ {{$valor_km ?? null}}</td>
            </tr>
        </table>

        <hr/>
        <h3>DESPESAS</h3>
        @if(count(unserialize($relatorio->despesas))>0) 

            @foreach(unserialize($relatorio->despesas) as $despesa)
                @if(count(unserialize($relatorio->despesas))>1) <h5>DESPESA {{$loop->iteration}}</h5> @endif
                <table>
                    <tbody>
                        <tr>
                            <td class="w50"><strong>DESCRIÇÃO: </strong>@if(!empty($despesa['DESCRIÇÃO'])) {{$despesa['DESCRIÇÃO'] ?? null}} @endif</td>
                            <td class="w50"><strong>CLIENTE: </strong>@if(!empty($despesa['CLIENTE'])) {{$despesa['CLIENTE'] ?? null}} @endif</td>
                        </tr>
                        <tr>
                            <td class="w50"><strong>PASTA: </strong>@if(!empty($despesa['PASTA'])) {{$despesa['PASTA'] ?? null}} @endif</td>
                            <td class="w50"><strong>VALOR: </strong>R$ @if(!empty($despesa['VALOR'])) {{round($despesa['VALOR'], 2) ?? null}} @endif</td>
                        </tr>
                    </tbody>
                </table>

                @php
                    if(!empty($despesa['VALOR'])){
                        $total_despesas += round($despesa['VALOR'], 2);
                    }
                @endphp

            @endforeach
            @if(count(unserialize($relatorio->despesas))>0) 
                <div class="total_despesas">
                    <strong>TOTAL DESPESAS: </strong>R$ {{$total_despesas ?? null}}
                </div>
                <br/>
            @endif

        @else
            <h5>NENHUMA DESPESA CADASTRADA</h5>
        @endif
        <br/>    
        <hr/>
        <h3>VALORES</h3>
        <table>
            <tbody>
                <tr>
                    <td class="w333" style="color:red;"><strong>VALOR CAUÇÃO: </strong>R$ {{$relatorio->caucao ?? null}}</td>
                    <td class="w333" style="color:blue;"><strong>VALOR TOTAL DA VIAGEM: </strong>R$ {{$total_despesas+$valor_km ?? null}}</td>
                    <td class="w333" style="color:green;"><strong>VALOR A SER DEVOLVIDO: </strong>R$ {{($total_despesas+$valor_km)-$relatorio->caucao ?? null}}</td>
                </tr>
            </tbody>
        </table>

        <hr/>
        <h3>OBSERVAÇÕES</h3>
        @if(!empty($relatorio->observacoes)) 
        <table>
            <tbody>
                <tr>
                    <td>{{$relatorio->observacoes ?? null}}</td>
                </tr>
            </tbody>
        </table>
        @else
            <h5>NENHUMA OBSERVAÇÃO CADASTRADA</h5>
        @endif
        <hr/>
        <p class="declaracao">
            EU, <strong>{{mb_strtoupper(Auth::user()->name, 'UTF-8') ?? null}}</strong>, DECLARO PARA OS DEVIDOS FINS, 
            TER RECEBIDO O VALOR DESCRITO NO CAMPO "VALOR A SER DEVOLDIDO".
        </p>
        <p class="declaracao">CAMPINAS, {{mb_strtoupper($dataextenso, 'UTF-8')}}.</p>

    </div>

</body>
</html>