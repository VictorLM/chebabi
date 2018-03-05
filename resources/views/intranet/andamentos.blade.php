@extends('intranet.templates.template')

@section('content')

<div class="container">
  

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="container-fluid" style="padding:0!important;">

                <h2>
                    <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                    Andamentos não oficiais Data Cloud - Legal One 
                </h2>
                
                <div class="form-relatorios">
                    <form class="form-inline" method="POST" id="form_andamentos" action="{{action('APIs\LegalOneController@andamentos_datacloud_filtrados')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <small>Área:</small><br/>
                            <select name="area" class="form-control" form="form_andamentos">
                                <option></option>
                                <option value="Cível" @if(isset($area) && $area == 'Cível') selected @endif>Cível</option>
                                <option value="Trabalhista" @if(isset($area) && $area == 'Trabalhista') selected @endif>Trabalhista</option>
                                <option value="BR Trabalhista" @if(isset($area) && $area == 'BR Trabalhista') selected @endif>BR Trabalhista</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <small>Posição:</small><br/>
                            <select name="posicao" class="form-control" form="form_andamentos">
                                <option></option>
                                <option value="Autor" @if(isset($posicao) && $posicao == 'Autor') selected @elseif (old('posicao') == 'Autor') selected @endif>Autor/Reclamante</option>
                                <option value="Réu" @if(isset($posicao) && $posicao == 'Réu') selected @elseif (old('posicao') == 'Réu') selected @endif>Réu/Reclamada</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <small>Pasta:</small><br/>
                            <input type="text" class="form-control" name="pasta" id="pasta" maxlength="11" 
                            value="@if(isset($pasta) && !empty($pasta)){{$pasta}}@else{{old('pasta')}}@endif">
                        </div>
                        <div class="form-group">
                            <small>Cliente:</small><br/>
                            <input type="text" class="form-control" name="cliente" id="cliente" maxlength="100" 
                            value="@if(isset($cliente) && !empty($cliente)){{$cliente}}@else{{old('cliente')}}@endif">
                        </div>
                        <div class="form-group">
                            <small>Cadastrado a partir de:</small><br/>
                            <input type="date" class="form-control" name="data" maxlength="10" 
                            value="@if(isset($data) && !empty($data)){{$data}}@else{{old('data')}}@endif">
                        </div>
                        <div class="form-group">
                            <small></small><br/>
                            <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
                            <a href="{{url('/intranet/andamentos-datacloud')}}">
                                <button type="button" class="btn btn-danger"><i class="glyphicon glyphicon-erase"></i> Limpar</button>
                            </a>
                        </div>
                    </form>
                </div>
                
                @if(isset($andamentos_filtrados) && isset($count))
                    <small><b><u>{{$count}}</u> Andamentos encontrados de acordo com os filtros aplicados acima.</b></small>
                @else
                    <small>Exibindo os 20 mais recentes. Use os filtros para visualizar todos.</small>
                @endif
                <br/><small>Ordenando pela data de cadastro do andamento no Legal One.</small>
                <br/><small><i>* Andamentos capturados dos sites dos tribunais, não dos diários oficiais.</i></small>
            </div>
        </div>

        <div class="panel-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Área</th>
                        <th>Pasta</th>
                        <th>Processo</th>
                        <th>Cliente</th>
                        <th>Posição Cliente</th>
                        <th>Parte Contrária</th>
                        <th>Disponivel em</th>
                        <th>Cadastrado em</th>
                        <th>Descrição Andamento</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($andamentos_filtrados))
                    
                        @foreach($andamentos_filtrados as $andamento)
                            <tr>
                                <td>{{$andamento->area}}</td>
                                <td><a href="http://iziquechebabi.novajus.com.br/processos/processos/Details/{{$andamento->pasta_id}}" target="_blank">{{$andamento->pasta}}</a></td>
                                <td>{{$andamento->processo}}</td>
                                <td>{{$andamento->cliente}}</td>
                                <td>{{$andamento->posicao}}</td>
                                <td>{{$andamento->contrario}}</td>
                                <td>{{Carbon\Carbon::parse($andamento->created_at)->format('d/m/y H:i')}}</td>
                                <td>{{Carbon\Carbon::parse($andamento->updated_at)->format('d/m/y H:i')}}</td>
                                <td>
                                    @if(strlen($andamento->descricao) > 200) 
                                        {{str_limit($andamento->descricao, 200, '...')}} 
                                        <br/><a href="{{url("/intranet/andamentos-datacloud/andamento/$andamento->id")}}" target="_blank">Visualizar andamento completo</a>
                                        <br/><a href="{{url("http://iziquechebabi.novajus.com.br/processos/andamentos/Details/$andamento->id?parentId=$andamento->pasta_id")}}" target="_blank">Visualizar no Legal One</a>
                                    @else
                                        {{$andamento->descricao}}
                                        <br/><a href="{{url("http://iziquechebabi.novajus.com.br/processos/andamentos/Details/$andamento->id?parentId=$andamento->pasta_id")}}" target="_blank">Visualizar no Legal One</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    @else

                        @foreach($andamentos as $andamento)
                            <tr>
                                <td>{{$andamento->area}}</td>
                                <td><a href="http://iziquechebabi.novajus.com.br/processos/processos/Details/{{$andamento->pasta_id}}" target="_blank">{{$andamento->pasta}}</a></td>
                                <td>{{$andamento->processo}}</td>
                                <td>{{$andamento->cliente}}</td>
                                <td>{{$andamento->posicao}}</td>
                                <td>{{$andamento->contrario}}</td>
                                <td>{{Carbon\Carbon::parse($andamento->created_at)->format('d/m/y H:i')}}</td>
                                <td>{{Carbon\Carbon::parse($andamento->updated_at)->format('d/m/y H:i')}}</td>
                                <td>
                                    @if(strlen($andamento->descricao) > 200) 
                                        {{str_limit($andamento->descricao, 200, '...')}} 
                                        <br/><a href="{{url("/intranet/andamentos-datacloud/andamento/$andamento->id")}}" target="_blank">Visualizar andamento completo</a>
                                        <br/><a href="{{url("http://iziquechebabi.novajus.com.br/processos/andamentos/Details/$andamento->id?parentId=$andamento->pasta_id")}}" target="_blank">Visualizar no Legal One</a>
                                    @else
                                        {{$andamento->descricao}}
                                        <br/><a href="{{url("http://iziquechebabi.novajus.com.br/processos/andamentos/Details/$andamento->id?parentId=$andamento->pasta_id")}}" target="_blank">Visualizar no Legal One</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    @endif
 
                </tbody>
            </table>
            @if(isset($andamentos_filtrados))
                {!! $andamentos_filtrados->appends(Request::only(['area'=>'area', 'pasta'=>'pasta', 'posicao'=>'posicao', 'cliente'=>'cliente', 'data'=>'data']))->links() !!}
            @endif

        </div>
    </div>

</div>

@endsection