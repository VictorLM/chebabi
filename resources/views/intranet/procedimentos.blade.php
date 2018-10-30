@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2 class="display-inline"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Procedimentos</h2>
        <input type="text" class="form-control" id="pesquisa-mark-js" placeholder="Pesquisar" autofocus>
        </div>

        <div class="panel-body">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                    <th>Procedimento</th>
                    <th>Tipo</th>
                    <th>Incluido em</th>
                    <th>Link para visualização</th>
                    </tr>
                </thead>
                <tbody id="tbody">
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

        </div>

    </div>
  
</div>

@push ('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
  <script src="{{asset('assets/js/mark-js-pesquisa.js')}}"></script>
@endpush

@endsection