@extends('intranet.templates.template')

@section('content')

<div class="container-fluid">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Editar Advogados</h2>
            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>Nome</th>
                        <th>Adv(s) Cível(is)</th>
                        <th>Adv(s) Trabalhistas(s)</th>
                        <th>Empresas</th>
                        <th>Cadastrado em</th>
                        <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($clientes as $cliente)
                        <tr @if(!$cliente->ativo) class="danger" @endif>
                            <td>{{$cliente->nome}}</td>
                            <td>
                                @for ($i=1; $i<=3; $i++)
                                    @if(!empty($cliente->{"adv_civel_".$i}))
                                        {{$cliente->{"advogado_civel_".$i}->name}}
                                    @endif
                                @endfor
                            </td>
                            <td>
                                @for ($i=1; $i<=3; $i++)
                                    @if(!empty($cliente->{"adv_trab_".$i}))
                                        {{$cliente->{"advogado_trab_".$i}->name}}
                                    @endif
                                @endfor
                            </td>
                            <td>
                                @if(!empty($cliente->empresas))
                                    <ul style="font-size:0.8em;">
                                    @foreach(json_decode($cliente->empresas) as $empresa)
                                        @if(!empty($empresa) && $empresa != " ")
                                            <li>{{$empresa}}</li>
                                        @endif
                                    @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>{{Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y')}}</td>
                            <td>
                                @if($cliente->ativo)
                                    <form method="POST" action="{{action('Admin\AdminController@delete_cliente', $cliente->id)}}">
                                        {{ csrf_field() }}
                                        <a href="{{action('Admin\AdminController@edit_cliente', $cliente->id)}}">
                                            <button class="btn btn-primary btn-block" type="button">
                                                <i class="glyphicon glyphicon-pencil"></i> Editar
                                            </button>
                                        </a>
                                        <br/>
                                        <button class="btn btn-danger btn-block del-btn" type="submit">
                                            <i class="glyphicon glyphicon-trash"></i> Excluir
                                        </button>
                                    </form>
                                @else
                                    CLIENTE EXCLUÍDO
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            
                {!! $clientes->links() !!}

            </div>

        </div>

</div>

@push ('scripts')
    <script src="{{asset('assets/js/admin_clientes.js')}}"></script>
@endpush

@endsection


