@extends('intranet.templates.template')

@section('content')

<div class="container-fluid">

        <div class="panel panel-default">

            <div class="panel-heading">
                
                <div class="container-fluid" style="padding:0!important;">

                    <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Relatórios de viagem</h2>
    
                    <div class="col-xs-12 form-relatorios">

                        <div class="col-xs-12 form-relatorios">
                            <form class="form-inline" method="POST" id="form_relatorios" action="{{action('Relatorio\RelatorioController@relatorios_user')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <select name="user" class="form-control" form="form_relatorios" required="required">
                                        <option value="">Filtrar por usuário</option>
                                        @foreach($users as $user)
                                            <option value="
                                                    {{$user->id}}" @if(!empty($relatorios_user) && 
                                                    $relatorios_user->count()>0 &&
                                                    $relatorios_user[0]->usuario == $user->id) 
                                                    selected @endif>{{$user->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
                                    <a href="{{url('/intranet/admin/relatorios')}}">
                                        <button type="button" class="btn btn-danger"><i class="glyphicon glyphicon-erase"></i> Limpar</button>
                                    </a>
                                </div>
                            </form>
                        </div>

                        @if(isset($relatorios_user) && count($relatorios_user)>0)
                            <small>* Exibindo apenas os relatórios do usuário selecionado acima.</small>
                        @else
                            <small style="color:red;">* Nenhum relatório encontrado.</small>
                        @endif
                        
                    </div>

                </div>

            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>Data envio / Data viagem</th>
                        <th>Tipo de viagem</th>
                        <th>Usuário</th>
                        <th>Cliente(s)</th>
                        <th>Veículo</th>
                        <th>Descrição(ões)</th>
                        <th>Km</th>
                        <th>Total despesas</th>
                        <th>Caução</th>
                        <th>À ser devolvido</th>
                        <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(isset($relatorios_user) && count($relatorios_user)>0)
                            
                            @foreach($relatorios_user as $relatorio)

                                <tr>
                                    <td>
                                        {{Carbon\Carbon::parse($relatorio->created_at)->format('d/m/Y') ?? null}} / 
                                        {{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y') ?? null}}
                                    </td>
                                    <td>@if($relatorio->kilometragem) COM KM @else SEM KM @endif</td>
                                    <td>{{mb_strtoupper($relatorio->nome_usuario->name ?? null, 'UTF-8')}}</td>
                                    <td>
                                        @foreach(unserialize($relatorio->clientes) as $cliente)
                                            {{$cliente['CLIENTE'] ?? null}}; 
                                        @endforeach 
                                    </td>
                                    <td>{{$relatorio->veiculo ?? null}}</td>
                                    <td>
                                        @foreach(unserialize($relatorio->clientes) as $cliente)
                                            {{$cliente['DESCRICAO'] ?? null}}; 
                                        @endforeach 
                                    </td>
                                    <td>
                                        {{$relatorio->totalkm ?? null}} Km / <br/> 
                                        @if($relatorio->veiculo == "ESCRITÓRIO")
                                            R$ 0,00
                                        @elseif($relatorio->veiculo == "PARTICULAR")
                                            R$ {{round($relatorio->totalkm*0.8 ?? null, 2)}}
                                        @else
                                            R$ 0,00
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        $total_despesas = 0;
                                        foreach(unserialize($relatorio->despesas) as $despesa){
                                            if(!empty($despesa['VALOR'])){
                                                $total_despesas += round($despesa['VALOR'], 2);
                                            }
                                        }
                                        @endphp
                                        R$ {{$total_despesas ?? null}}
                                    </td>
                                    <td>R$ {{$relatorio->caucao ?? null}}</td>
                                    <td>
                                        @if($relatorio->veiculo == "ESCRITÓRIO")
                                            R$ {{$total_despesas-$relatorio->caucao ?? null}}
                                        @elseif($relatorio->veiculo == "PARTICULAR")
                                            R$ {{($total_despesas+round($relatorio->totalkm*0.8, 2))-$relatorio->caucao ?? null}}
                                        @else
                                            R$ {{$total_despesas-$relatorio->caucao ?? null}}
                                        @endif                                        
                                    </td>
                                    <td>{{str_limit($relatorio->observacoes ?? null, 30, '...')}}</td>
                                </tr>

                            @endforeach

                        @else
                            <h3 style="color:red;">Nenhum relatório encontrado.</h3>
                        @endif

                    </tbody>
                </table>
                
                @if(isset($relatorios_user) && count($relatorios_user)>0)
                    {!! $relatorios_user->appends(Request::only(['user'=>'user']))->links() !!}
                @endif

            </div>
        </div>  
    </div>

@endsection