@extends('intranet.templates.template')

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Editar Advogados</h2>
            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>Nome</th>
                        <th>OAB</th>
                        <th>Texto</th>
                        <th>Foto</th>
                        <th>Cadastrado em</th>
                        <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($advs as $adv)
                        <tr>
                            <td>{{$adv->nome_usuario->name}}</td>
                            <td>{{$adv->oab}}</td>
                            <td>{{$adv->texto}}</td>
                            <td><img src="@if(!empty($adv->foto)){{url($adv->foto)}}@endif" width="100px"></td>
                            <td>{{Carbon\Carbon::parse($adv->created_at)->format('d/m/Y')}}</td>
                            <td>
                                <form method="POST" action="{{action('Advogados\AdvogadosController@destroy', $adv->id)}}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                
                                    <a href="{{action('Advogados\AdvogadosController@edit', $adv->id)}}">
                                        <button class="btn btn-primary" type="button">
                                            <i class="glyphicon glyphicon-pencil"></i> Editar
                                        </button>
                                    </a>
                
                                    <button class="btn btn-danger" type="submit">
                                        <i class="glyphicon glyphicon-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            
                {!! $advs->links() !!}

            </div>

        </div>

</div>

@endsection


