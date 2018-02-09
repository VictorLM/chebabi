@extends('intranet.templates.template')

@section('content')

<div class="container">
  <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Procedimentos</h2>          
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Procedimento</th>
        <th>Tipo</th>
        <th>Incluido em</th>
        <th>Link para visualização</th>
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
            <td><a href="{{$procedimento->link}}" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> Visualizar</a></td>
        </tr>
    @endforeach
    </tbody>
  </table>

  {!! $procedimentos->links() !!}
  
</div>

@endsection


