@extends('intranet.templates.template')

@section('content')

<div class="container">
  <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Excluir Procedimento</h2>          
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Incluido em</th>
        <th>Excluir</th>
      </tr>
    </thead>
    <tbody>
    @foreach($procedimentos as $procedimento)
        <tr>
            <td>{{$procedimento->name}}</td>
            <td>
                @switch($procedimento->tipo)
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
            <td>{{Carbon\Carbon::parse($procedimento->created_at)->format('d/m/y')}}</td>
            <td>
                <form method="POST" action="{{action('Admin\AdminController@del_procedimento')}}">
                    {!! csrf_field() !!}
                    <input type="text" name="id" value="{{$procedimento->id}}" hidden="hidden">
                    <button class="btn btn-danger" type="submit">
                        <i class="glyphicon glyphicon-trash"></i> Excluir
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
  </table>

  {!! $procedimentos->links() !!}
  
</div>

@endsection


