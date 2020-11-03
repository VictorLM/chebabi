@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@push('meta')
    <meta http-equiv="refresh" content="60"/>
@endpush

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Reservar Estação de Trabalho - {{Carbon\Carbon::parse($dia)->format('d/m/Y')}}
            </h2>
        </div>

        <div class="panel-body">

            {{-- Alertas flash-messages --}}
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <li>{{$error}}</li>
                    </div>
                @endforeach
            @endif

            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                <div class="alert alert-{{$msg}} alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {!! Session::get('alert-' . $msg) !!}
                </div>
                @endif
            @endforeach

            <div class="row">

                <h3 class="text-center">
                    Nesta data temos disponivéis  
                    <b><span class="@if($estacoes_livres_dia_count > 0) text-success @else text-danger @endif">{{$estacoes_livres_dia_count}}</span> 
                    estações de trabalho</b> e 
                    <b><span class="@if($estacionamentos_livres_dia_count > 0) text-success @else text-danger @endif">{{$estacionamentos_livres_dia_count}}</span> 
                    vagas de estacionamento</b>.
                </h3>
                <hr/>

                @if($estacoes_livres_dia_count > 0)

                    <form class="form-horizontal" id="form" method="POST" action="{{action('ReservasEstacoes\ReservasEstacoesController@reservar')}}">
                        {{ csrf_field() }}

                        <div class="col-md-3 @if($errors->has('inicio')) has-error @endif">
                            <label>Início</label>
                            <input type="date" name="inicio" class="form-control input-lg" value="{{$dia}}" required readonly>
                        </div>

                        <div class="col-md-3 @if($errors->has('fim')) has-error @endif">
                            <label>Fim</label>
                            <input type="date" name="fim" class="form-control input-lg" value="{{old("fim") ?? $dia}}" required>
                        </div>

                        <div class="col-md-3 @if($errors->has('estacionamento')) has-error @endif">
                            <label>Reservar estacionamento?</label>
                            <select class="form-control input-lg" name="estacionamento" required @if($estacionamentos_livres_dia_count <= 0) disabled @endif>
                                <option value="">Selecione uma opção</option>
                                <option value="1" @if(old('estacionamento')=="1") selected @endif>Sim</option>
                                <option value="0" @if(old('estacionamento')=="0" || $estacionamentos_livres_dia_count <= 0) selected @endif>Não</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-lg input-lg form-control">Reservar</button>
                        </div>
                    </form>

                @endif
                
            </div>
            <br/>
            <h4 class="text-right"><a href="{{url('/intranet/reservar/minhas-reservas')}}">Minhas reservas</a></h4>
            <br/>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h2>Aguarde...</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>
  
</div>

@push ('scripts')
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>    
@endpush

@endsection
