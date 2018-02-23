@extends('intranet.templates.template')

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Editar Usuários</h2>
            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Tipo</th>
                        <th>Uaus</th>
                        <th>Último acesso</th>
                        <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr @if(!$user->ativo) class="danger" @endif>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                @switch($user->tipo)
                                    @case('admin')
                                        Administrador
                                        @break
                                    @case('adv')
                                        Advogado
                                        @break
                                    @case('adm')
                                        Administrativo
                                        @break
                                    @case('fin')
                                        Financeiro
                                        @break
                                    @case('admjur')
                                        Adm. Jurídico
                                        @break
                                    @case('geral')
                                        Geral
                                        @break
                                    @default
                                        Não definido
                                @endswitch
                            </td>
                            <td>{{$user->uaus}}</td>
                            <td>{{Carbon\Carbon::parse($user->last_login)->format('d/m/Y H:i')}}</td>
                            <td>
                                @if(!$user->ativo)
                                    <form method="POST" action="{{action('User\UserController@active', $user->id)}}">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                
                                        <button class="btn btn-success" type="submit">
                                            <i class="glyphicon glyphicon-flash"></i> Ativar
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{action('User\UserController@destroy', $user->id)}}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                
                                        <a href="{{action('User\UserController@edit', $user->id)}}">
                                            <button class="btn btn-primary" type="button">
                                                <i class="glyphicon glyphicon-pencil"></i> Editar
                                            </button>
                                        </a>
                
                                        <button class="btn btn-danger" type="submit">
                                            <i class="glyphicon glyphicon-trash"></i> Desativar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            
                {!! $users->links() !!}

            </div>

        </div>

</div>

@endsection


