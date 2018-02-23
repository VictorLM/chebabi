@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="container-fluid" style="padding:0!important;">
                <h2>
                    <a href="{{url('/intranet/andamentos-datacloud')}}">
                    <i class="glyphicon glyphicon-arrow-left"></i></a> Andamento {{$andamento->id}}
                </h2>
            </div>
        </div>

        <div class="panel-body">
                
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Pasta</th>
                        <th>Cadastrada em</th>
                        <th>Processo</th>
                        <th>Cliente</th>
                        <th>Posição Cliente</th>
                        <th>Parte Contrária</th>
                        <th>Disponivel em</th>
                        <th>Cadastrado em</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$andamento->area}}</td>
                        <td><a href="http://iziquechebabi.novajus.com.br/processos/processos/Details/{{$andamento->pasta_id}}" target="_blank">{{$andamento->pasta}}</a></td>
                        <td>{{Carbon\Carbon::parse($andamento->data_cadastro_pasta)->format('d/m/y H:i')}}</td>
                        <td>{{$andamento->processo}}</td>
                        <td>{{$andamento->cliente}}</td>
                        <td>{{$andamento->posicao}}</td>
                        <td>{{$andamento->contrario}}</td>
                        <td>{{Carbon\Carbon::parse($andamento->created_at)->format('d/m/y H:i')}}</td>
                        <td>{{Carbon\Carbon::parse($andamento->updated_at)->format('d/m/y H:i')}}</td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="9"><b>Descrição Andamento:</b></td>
                    </tr>
                    <tr>
                        <td colspan="9">{{$andamento->descricao}}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    
    </div>

@endsection


