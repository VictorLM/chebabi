@extends('intranet.templates.template')

@section('content')

<div class="container">

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
                        @if(isset($relatorios) && count($relatorios)>0 ||
                        isset($relatorios_user) && count($relatorios_user)>0)
                            <small>*Últimos 200</small>
                        @endif
                        
                    </div>

                </div>

            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>Data do envio</th>
                        <th>Data da viagem</th>
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
                        @if(isset($relatorios_user))
                        
                            @if(count($relatorios_user)>0)
                            
                                @foreach($relatorios_user as $relatorio)
                                    <tr>
                                    <td>{{Carbon\Carbon::parse($relatorio->created_at)->format('d/m/Y H:i')}}</td>
                                    <td>{{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y')}}</td>
                                    <td>{{$relatorio->tipo_viagem}}</td>
                                    <td>{{$relatorio->nome_usuario->name}}</td>
                                    <td>{{$relatorio->cliente1}}; {{$relatorio->cliente2}}@if(!empty($relatorio->cliente2)); @endif{{$relatorio->cliente3}}</td>
                                    <td>{{$relatorio->carro}}</td>
                                    <td>{{$relatorio->motivoviagem1}}; {{$relatorio->motivoviagem2}}@if(!empty($relatorio->motivoviagem2)); @endif{{$relatorio->motivoviagem3}}</td>
                                    <td>{{$relatorio->totalkm}} Km - R${{round($relatorio->totalkm*0.8, 2)}}</td>
                                    <td>
                                        R$ {{round(((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                        (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                        (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                        (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0))
                                        , 2)}}
                                    </td>
                                    <td>R$ {{$relatorio->caucao}}</td>
                                    <td>
                                        @if($relatorio->carro == 'Particular')
                                            R$ {{round((!empty($relatorio->totalkm) ? $relatorio->totalkm*0.8 : 0) + 
                                            ((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                            (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                            (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                            (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0) -
                                            (!empty($relatorio->caucao) ? $relatorio->caucao : 0))
                                            , 2)}}
                                        @endif
                                        
                                        @if($relatorio->carro == 'Escritório')
                                            R$ {{round(((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                            (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                            (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                            (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0) -
                                            (!empty($relatorio->caucao) ? $relatorio->caucao : 0))
                                            , 2)}}
                                        @endif
                                        
                                        @if(empty($relatorio->carro))
                                            R$ {{round((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                            (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                            (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                            (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0) -
                                            (!empty($relatorio->caucao) ? $relatorio->caucao : 0)
                                            , 2)}}
                                        @endif
                                        
                                    </td>
                                    <td>{{str_limit($relatorio->observacoes, 30, '...')}}</td>
                                </tr>
                            @endforeach
                                
                            @else
                                <h3>Nenhum relatório cadastrado!</h3>
                            @endif
                            
                        @else
                        
                            @foreach($relatorios as $relatorio)
                                <tr>
                                    <td>{{Carbon\Carbon::parse($relatorio->created_at)->format('d/m/y H:i')}}</td>
                                    <td>{{Carbon\Carbon::parse($relatorio->data)->format('d/m/Y')}}</td>
                                    <td>{{$relatorio->tipo_viagem}}</td>
                                    <td>{{$relatorio->nome_usuario->name}}</td>
                                    <td>{{$relatorio->cliente1}}; {{$relatorio->cliente2}}@if(!empty($relatorio->cliente2)); @endif{{$relatorio->cliente3}}</td>
                                    <td>{{$relatorio->carro}}</td>
                                    <td>{{$relatorio->motivoviagem1}}; {{$relatorio->motivoviagem2}}@if(!empty($relatorio->motivoviagem2)); @endif{{$relatorio->motivoviagem3}}</td>
                                    <td>{{$relatorio->totalkm}} Km - R${{round($relatorio->totalkm*0.8, 2)}}</td>
                                    <td>
                                        R$ {{round(((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                        (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                        (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                        (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0))
                                        , 2)}}
                                    </td>
                                    <td>R$ {{$relatorio->caucao}}</td>
                                    <td>
                                        @if($relatorio->carro == 'Particular')
                                            R$ {{round((!empty($relatorio->totalkm) ? $relatorio->totalkm*0.8 : 0) + 
                                            ((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                            (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                            (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                            (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0) -
                                            (!empty($relatorio->caucao) ? $relatorio->caucao : 0))
                                            , 2)}}
                                        @endif
                                        
                                        @if($relatorio->carro == 'Escritório')
                                            R$ {{round(((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                            (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                            (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                            (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0) -
                                            (!empty($relatorio->caucao) ? $relatorio->caucao : 0))
                                            , 2)}}
                                        @endif
                                        
                                        @if(empty($relatorio->carro))
                                            R$ {{round((!empty($relatorio->despesasgerais1) ? $relatorio->despesasgerais1 : 0) +
                                            (!empty($relatorio->despesasgerais2) ? $relatorio->despesasgerais2 : 0) + 
                                            (!empty($relatorio->despesasgerais3) ? $relatorio->despesasgerais3 : 0) +
                                            (!empty($relatorio->despesasgerais4) ? $relatorio->despesasgerais4 : 0) -
                                            (!empty($relatorio->caucao) ? $relatorio->caucao : 0)
                                            , 2)}}
                                        @endif
                                        
                                    </td>
                                    <td>{{str_limit($relatorio->observacoes, 30, '...')}}</td>
                                </tr>
                            @endforeach
                            
                        @endif
                    </tbody>
                </table>
                
                @if(empty($relatorios_user))
                {!! $relatorios->links() !!}
                @else
                    {!! $relatorios_user->appends(Request::only(['user'=>'user']))->links() !!}
                @endif

            </div>
            
        </div>  

    </div>

@endsection