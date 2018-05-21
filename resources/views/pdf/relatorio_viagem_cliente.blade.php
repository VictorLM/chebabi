@php
    $total_despesas = 0;
    foreach(unserialize($relatorio->despesas) as $despesa){
        if($despesa['CLIENTE'] == unserialize($relatorio->clientes)[$i]["CLIENTE"] || $despesa['CLIENTE'] == "TODOS"){
            $total_despesas += round($despesa['VALOR'] ?? 0, 2);
        }
    }

    if($relatorio->kilometragem && !empty($relatorio->totalkm)){
        $valor_km = round($relatorio->totalkm*(unserialize($relatorio->clientes)[$i]['VALOR_KM']) ?? 0.8, 2);
    }else{
        $valor_km = 0;
    }
@endphp

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de viagem - {{$relatorio->identificador ?? null}}</title>
    <link href="../public/assets/css/relatorio.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <style>
        @page { margin: 10px; }
        body { margin: 10px; font-size: 1.2em;}
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
        <h2>RELATÓRIO DE VIAGEM - CLIENTE {{unserialize($relatorio->clientes)[$i]["CLIENTE"] ?? null}}</h2>
        <br/>
        <table>
            <tbody>
                <tr>
                    <th class="w1666 center"><strong>PARTE CONTRÁRIA:</strong></th>
                    <th class="w1666 center"><strong>PROCESSO:</strong></th>
                    <th class="w1666 center"><strong>PASTA:</strong></th>
                    <th class="w1666 center"><strong>DATA:</strong></th>
                    <th class="w1666 center"><strong>VALOR:</strong></th>
                    <th class="w1666 center"><strong>MOTIVO:</strong></th>
                </tr>
                <tr>
                    <td class="w1666">{{unserialize($relatorio->clientes)[$i]["CONTRARIO"] ?? null}}</td>
                    <td class="w1666">{{unserialize($relatorio->clientes)[$i]["PROCESSO"] ?? null}}</td>
                    <td class="w1666">{{unserialize($relatorio->clientes)[$i]["PASTA"] ?? null}}</td>
                    <td class="w1666">{{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y') ?? null}}</td>
                    <td class="w1666">R$ {{$total_despesas+$valor_km ?? null}}</td>
                    <td class="w1666">{{unserialize($relatorio->clientes)[$i]["DESCRICAO"] ?? null}}</td>
                </tr>
            </tbody>
        </table>

        <hr/>
        <h3>TRAJETO</h3>

        <table>
            <tbody>
                @if(count(unserialize($relatorio->enderecos))>0)  

                    @if(count(unserialize($relatorio->enderecos))==3)  
                        <tr>
                            <th class="w333 center"><strong>PARTIDA: </strong></th>
                            <th class="w333 center"><strong>DESTINO: </strong></th>
                            <th class="w333 center"><strong>RETORNO: </strong></th>
                        </tr>
                        <tr>
                            <td class="w333">{{unserialize($relatorio->enderecos)[0] ?? null}}</td>
                            <td class="w333">{{unserialize($relatorio->enderecos)[1] ?? null}}</td>
                            <td class="w333">{{unserialize($relatorio->enderecos)[2] ?? null}}</td>
                        </tr>
                    @elseif(count(unserialize($relatorio->enderecos))==4)  
                        <tr>
                            <th class="w25 center"><strong>PARTIDA: </strong></th>
                            <th class="w25 center"><strong>DESTINO 1: </strong></th>
                            <th class="w25 center"><strong>DESTINO 2: </strong></th>
                            <th class="w25 center"><strong>RETORNO: </strong></th>
                        </tr>
                        <tr>
                            <td class="w25">{{unserialize($relatorio->enderecos)[0] ?? null}}</td>
                            <td class="w25">{{unserialize($relatorio->enderecos)[1] ?? null}}</td>
                            <td class="w25">{{unserialize($relatorio->enderecos)[2] ?? null}}</td>
                            <td class="w25">{{unserialize($relatorio->enderecos)[3] ?? null}}</td>
                        </tr>
                    @elseif(count(unserialize($relatorio->enderecos))==5)  
                        <tr>
                            <th class="w20 center"><strong>PARTIDA: </strong></th>
                            <th class="w20 center"><strong>DESTINO 1: </strong></th>
                            <th class="w20 center"><strong>DESTINO 2: </strong></th>
                            <th class="w20 center"><strong>DESTINO 3: </strong></th>
                            <th class="w20 center"><strong>RETORNO: </strong></th>
                        </tr>
                        <tr>
                            <td class="w20">{{unserialize($relatorio->enderecos)[0] ?? null}}</td>
                            <td class="w20">{{unserialize($relatorio->enderecos)[1] ?? null}}</td>
                            <td class="w20">{{unserialize($relatorio->enderecos)[2] ?? null}}</td>
                            <td class="w20">{{unserialize($relatorio->enderecos)[3] ?? null}}</td>
                            <td class="w20">{{unserialize($relatorio->enderecos)[4] ?? null}}</td>
                        </tr>
                    @endif

                @else
                    <h5>NENHUM TRAJETO CADASTRADO</h5>
                @endif
            </tbody>
        </table>
        <br/>
        <hr/>
        <h3>DESPESAS</h3>

        @if(!empty($valor_km) && count(unserialize($relatorio->despesas))>0)

            <table>
                <tr>
                    <th class="w50 center"><strong>DESCRIÇÃO</strong></th>
                    <th class="w50 center"><strong>CUSTO:</strong></th>
                </tr>
                <tr>
                    <td class="w50">KILOMETRAGEM ({{$relatorio->totalkm ?? null}} KM x R$ {{unserialize($relatorio->clientes)[$i]['VALOR_KM'] ?? 0.80}})</td>
                    <td class="w50">R$ {{$valor_km ?? null}}</td>
                </tr>
                @foreach(unserialize($relatorio->despesas) as $despesa)
                    @if($despesa['CLIENTE'] == unserialize($relatorio->clientes)[$i]["CLIENTE"] || $despesa['CLIENTE'] == "TODOS")
                        <tr>
                            <td class="w50">@if(!empty($despesa['DESCRIÇÃO'])) {{$despesa['DESCRIÇÃO'] ?? null}} @endif</td>
                            <td class="w50">R$ @if(!empty($despesa['VALOR'])) {{round($despesa['VALOR'], 2) ?? null}} @endif</td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <hr/>

        @elseif(!empty($valor_km))

            <table>
                <tr>
                    <th class="w50 center"><strong>DESCRIÇÃO</strong></th>
                    <th class="w50 center"><strong>CUSTO:</strong></th>
                </tr>
                <tr>
                    <td class="w50">KILOMETRAGEM ({{$relatorio->totalkm ?? null}} KM x R$ {{unserialize($relatorio->clientes)[$i]['VALOR_KM'] ?? 0.80}})</td>
                    <td class="w50">R$ {{$valor_km ?? null}}</td>
                </tr>
            </table>
            <hr/>
            
        @elseif(count(unserialize($relatorio->despesas))>0)

            <table>
                <tr>
                    <th class="w50 center"><strong>DESCRIÇÃO</strong></th>
                    <th class="w50 center"><strong>CUSTO:</strong></th>
                </tr>
                @foreach(unserialize($relatorio->despesas) as $despesa)
                    @if($despesa['CLIENTE'] == unserialize($relatorio->clientes)[$i]["CLIENTE"] || $despesa['CLIENTE'] == "TODOS")
                        <tr>
                            <td class="w50">@if(!empty($despesa['DESCRIÇÃO'])) {{$despesa['DESCRIÇÃO'] ?? null}} @endif</td>
                            <td class="w50">R$ @if(!empty($despesa['VALOR'])) {{round($despesa['VALOR'], 2) ?? null}} @endif</td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <hr/>

        @else
            <h5>NENHUMA DESPESA CADASTRADA</h5>
            <hr/>
        @endif

        <table class="valores">
            <tr>
                <td><strong>TOTAL KM (R$ {{unserialize($relatorio->clientes)[$i]['VALOR_KM'] ?? 0.80}} / KM): </strong></td>
                <td>R$ {{$valor_km ?? null}}</td>
            </tr>
            <tr>
                <td><strong>TOTAL DESPESAS: </strong></td>
                <td>R$ {{$total_despesas ?? null}}</td>
            </tr>
            <tr>
                <td><strong>TOTAL (KM + DESPESAS): </strong></td>
                <td>R$ {{$total_despesas+$valor_km ?? null}}</td>
            </tr>
        </table>
        <hr/>
    </div>
    <footer><strong>ADVOGADO:</strong> {{mb_strtoupper(Auth::user()->name, 'UTF-8') ?? null}}</footer>

</body>
</html>