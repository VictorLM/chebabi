@php
    $total_despesas = 0;
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
        @page { margin: 5px; }
        body { margin: 5px; }
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
        <h1>RELATÓRIO DE VIAGEM - {{$relatorio->identificador}}</h1>
        <table>
            <tbody>
                <tr>
                    <td><strong>RESPONSÁVEL: </strong>{{mb_strtoupper(Auth::user()->name, 'UTF-8')}}</td>
                    <td><strong>TIPO DE VIAGEM: </strong>@if($relatorio->kilometragem) COM KILOMETRAGEM @else SEM KILOMETRAGEM @endif</td>
                </tr>
                <tr>
                    <td><strong>VEÍCULO: </strong>{{$relatorio->veiculo}}</td>
                    <td><strong>REEMBOLSÁVEL: </strong>@if($relatorio->reembolsavel) SIM @else NÃO @endif</td>
                </tr>
            </tbody>
        </table>

        <hr/>
        <h3>
            @if(count(unserialize($relatorio->clientes))>1) INFORMAÇÕES DOS CLIENTES @else INFORMAÇÕES DO CLIENTE @endif
        </h3>
        @foreach(unserialize($relatorio->clientes) as $cliente)
            @if(count(unserialize($relatorio->clientes))>1) <h5>CLIENTE {{$loop->iteration}}</h5> @endif
            <table>
                <tbody>
                    <tr>
                        <td colspan="2"><strong>CLIENTE: </strong>{{$cliente['CLIENTE']}}</td>
                        <td><strong>PARTE CONTRÁRIA: </strong>{{$cliente['CONTRARIO']}}</td>
                    </tr>
                    <tr>
                        <td><strong>PASTA: </strong>{{$cliente['PASTA']}}</td>
                        <td><strong>PROCESSO: </strong>{{$cliente['PROCESSO']}}</td>
                        <td><strong>MOTIVO VIAGEM: </strong>{{$cliente['DESCRICAO']}}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach

        <hr/>
        <h3>INFORMAÇÕES DA VIAGEM</h3>
        <table>
            <tbody>
                @if(count(unserialize($relatorio->enderecos))>0)  

                    @if(count(unserialize($relatorio->enderecos))==3)  
                        <tr>
                            <th><strong>PARTIDA: </strong></th>
                            <th colspan="2"><strong>DESTINO: </strong></th>
                            <th><strong>RETORNO: </strong></th>
                        </tr>
                        <tr>
                            <td>{{unserialize($relatorio->enderecos)[0]}}</td>
                            <td colspan="2">{{unserialize($relatorio->enderecos)[1]}}</td>
                            <td>{{unserialize($relatorio->enderecos)[2]}}</td>
                        </tr>
                    @elseif(count(unserialize($relatorio->enderecos))==4)  
                        <tr>
                            <th><strong>PARTIDA: </strong></th>
                            <th><strong>DESTINO: </strong></th>
                            <th><strong>DESTINO 2: </strong></th>
                            <th><strong>RETORNO: </strong></th>
                        </tr>
                        <tr>
                            <td>{{unserialize($relatorio->enderecos)[0]}}</td>
                            <td>{{unserialize($relatorio->enderecos)[1]}}</td>
                            <td>{{unserialize($relatorio->enderecos)[2]}}</td>
                            <td>{{unserialize($relatorio->enderecos)[3]}}</td>
                        </tr>
                    @endif

                @endif

                <tr>
                    <td><strong>DATA DA VIAGEM: </strong>{{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y')}}</td>
                    <td><strong>PEDÁGIO: </strong>@if($relatorio->pedagio) SIM @endif</td>
                    <td><strong>TOTAL KM: </strong>{{$relatorio->totalkm}}</td>
                    <td><strong>TOTAL KM (R$): </strong>R$ {{$valor_km}}</td>
                </tr>

            </tbody>
        </table>

        <hr/>
        <h3>DESPESAS</h3>
        @if(count(unserialize($relatorio->despesas))>0) 

            @foreach(unserialize($relatorio->despesas) as $despesa)
                @if(count(unserialize($relatorio->despesas))>1) <h5>DESPESA {{$loop->iteration}}</h5> @endif
                <table>
                    <tbody>
                        <tr>
                            <td><strong>DESCRIÇÃO: </strong>@if(!empty($despesa['DESCRIÇÃO'])) {{$despesa['DESCRIÇÃO']}} @endif</td>
                            <td><strong>CLIENTE: </strong>@if(!empty($despesa['CLIENTE'])) {{$despesa['CLIENTE']}} @endif</td>
                        </tr>
                        <tr>
                            <td><strong>PASTA: </strong>@if(!empty($despesa['PASTA'])) {{$despesa['PASTA']}} @endif</td>
                            <td><strong>VALOR: </strong>R$ @if(!empty($despesa['VALOR'])) {{round($despesa['VALOR'], 2)}} @endif</td>
                        </tr>
                    </tbody>
                </table>
                <br/>

                @php
                    if(!empty($despesa['VALOR'])){
                        $total_despesas += round($despesa['VALOR'], 2);
                    }
                @endphp

            @endforeach
            @if(count(unserialize($relatorio->despesas))>0) 
                <br/>
                <div class="total_despesas">
                    <strong>TOTAL DESPESAS: </strong>R$ {{$total_despesas}}
                </div>
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
                    <td style="color:red;"><strong>VALOR CAUÇÃO: </strong>R$ {{$relatorio->caucao}}</td>
                    <td style="color:blue;"><strong>VALOR TOTAL DA VIAGEM: </strong>R$ {{$total_despesas+$valor_km}}</td>
                    <td style="color:green;"><strong>VALOR A SER DEVOLVIDO: </strong>R$ {{($total_despesas+$valor_km)-$relatorio->caucao}}</td>
                </tr>
            </tbody>
        </table>

        <hr/>
        <h3>OBSERVAÇÕES</h3>
        @if(!empty($relatorio->observacoes)) 
        <table>
            <tbody>
                <tr>
                    <td>{{$relatorio->observacoes}}</td>
                </tr>
            </tbody>
        </table>
        @else
            <h5>NENHUMA OBSERVAÇÃO CADASTRADA</h5>
        @endif
        <hr/>
        <p class="declaracao">
            EU, <strong>{{mb_strtoupper(Auth::user()->name, 'UTF-8')}}</strong>, DECLARO PARA OS DEVIDOS FINS, 
            TER RECEBIDO O VALOR DESCRITO NO CAMPO "VALOR A SER DEVOLDIDO".
        </p>
        <p class="declaracao">CAMPINAS, {{mb_strtoupper(strftime("%d de %B de %Y"), 'UTF-8')}}.</p>

    </div>

</body>
</html>