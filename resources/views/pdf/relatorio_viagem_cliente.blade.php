@php
    $total_despesas = 0;
    foreach(unserialize($relatorio->despesas) as $despesa){
        if(!empty($despesa['VALOR'])){
            $total_despesas += round($despesa['VALOR'], 2);
        }
    }

    if($relatorio->veiculo == "ESCRITÓRIO"){
        $valor_km = 0;
    }elseif($relatorio->veiculo == "PARTICULAR"){
        $valor_km = round($relatorio->totalkm*0.8, 2);
    }else{
        $valor_km = 0;
    }
@endphp

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de viagem - {{$relatorio->identificador}}</title>
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
            <img src="../public/assets/imagens/logo_pq.png">
        </div>
        <div class="datetime">
            <small>{{Carbon\Carbon::parse($relatorio->created_at)->format('d/m/Y H:i')}}</small>
        </div>

        <br/><br/><br/>
        <h2>RELATÓRIO DE VIAGEM - CLIENTE {{unserialize($relatorio->clientes)[$i]["CLIENTE"]}}</h2>
        <br/>
        <table>
            <tbody>
                <tr>
                    <th class="center"><strong>PARTE CONTRÁRIA:</strong></th>
                    <th class="center"><strong>PROCESSO:</strong></th>
                    <th class="center"><strong>PASTA:</strong></th>
                    <th class="center"><strong>DATA:</strong></th>
                    <th class="center"><strong>VALOR:</strong></th>
                    <th class="center"><strong>MOTIVO:</strong></th>
                </tr>
                <tr>
                    <td>{{unserialize($relatorio->clientes)[$i]["CONTRARIO"]}}</td>
                    <td>{{unserialize($relatorio->clientes)[$i]["PROCESSO"]}}</td>
                    <td>{{unserialize($relatorio->clientes)[$i]["PASTA"]}}</td>
                    <td>{{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y')}}</td>
                    <td>R$ {{$total_despesas+$valor_km}}</td>
                    <td>{{unserialize($relatorio->clientes)[$i]["DESCRICAO"]}}</td>
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
                            <td class="w333">{{unserialize($relatorio->enderecos)[0]}}</td>
                            <td class="w333">{{unserialize($relatorio->enderecos)[1]}}</td>
                            <td class="w333">{{unserialize($relatorio->enderecos)[2]}}</td>
                        </tr>
                    @elseif(count(unserialize($relatorio->enderecos))==4)  
                        <tr>
                            <th class="w25 center"><strong>PARTIDA: </strong></th>
                            <th class="w25 center"><strong>DESTINO: </strong></th>
                            <th class="w25 center"><strong>DESTINO 2: </strong></th>
                            <th class="w25 center"><strong>RETORNO: </strong></th>
                        </tr>
                        <tr>
                            <td class="w25">{{unserialize($relatorio->enderecos)[0]}}</td>
                            <td class="w25">{{unserialize($relatorio->enderecos)[1]}}</td>
                            <td class="w25">{{unserialize($relatorio->enderecos)[2]}}</td>
                            <td class="w25">{{unserialize($relatorio->enderecos)[3]}}</td>
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
        @if(count(unserialize($relatorio->despesas))>0) 

            <table>
                <tbody>
                    <tr>
                        <th class="w25 center"><strong>DESCRIÇÃO</strong></th>
                        <th class="w25 center"><strong>CUSTO:</strong></th>
                    </tr>
                    <tr>
                        <td class="w25">KILOMETRAGEM ({{$relatorio->totalkm}} KM x R$0,80)</td>
                        <td class="w25">R$ {{$valor_km}}</td>
                    </tr>
                    @foreach(unserialize($relatorio->despesas) as $despesa)
                        <tr>
                            <td class="w25">@if(!empty($despesa['DESCRIÇÃO'])) {{$despesa['DESCRIÇÃO']}} @endif</td>
                            <td class="w25">R$ @if(!empty($despesa['VALOR'])) {{round($despesa['VALOR'], 2)}} @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr/>
        @else
            <h5>NENHUMA DESPESA CADASTRADA</h5>
            <hr/>
        @endif
            <table class="valores">
                <tr>
                    <td><strong>TOTAL KM: </strong></td>
                    <td>R$ {{$valor_km}}</td>
                </tr>
                <tr>
                    <td><strong>TOTAL DESPESAS: </strong></td>
                    <td>R$ {{$total_despesas}}</td>
                </tr>
                <tr>
                    <td><strong>TOTAL (KM + DESPESAS): </strong></td>
                    <td>R$ {{$total_despesas+$valor_km}}</td>
                </tr>
            </table>
        <hr/>
    </div>
    <footer><strong>ADVOGADO:</strong> {{mb_strtoupper(Auth::user()->name, 'UTF-8')}}</footer>

</body>
</html>