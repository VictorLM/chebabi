@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Editar Reserva
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

                <form class="form-horizontal" id="form" method="POST" action="{{action('ReservasEstacoes\ReservasEstacoesController@editar', $reserva->id)}}">
                    {{ csrf_field() }}

                    <div class="col-md-3 @if($errors->has('inicio')) has-error @endif">
                        <label>Início</label>
                        <input type="date" name="inicio" class="form-control input-lg" value="{{$reserva->inicio}}" required readonly>
                    </div>

                    <div class="col-md-3 @if($errors->has('fim')) has-error @endif">
                        <label>Fim</label>
                        <input type="date" name="fim" class="form-control input-lg" value="{{old("fim") ?? $reserva->fim}}" required>
                    </div>

                    <div class="col-md-3 @if($errors->has('estacionamento')) has-error @endif">
                        <label>Reservar estacionamento?</label>
                        <select class="form-control input-lg" name="estacionamento" required>
                            <option value="">Selecione uma opção</option>
                            <option value="1" @if(old('estacionamento')=="1" || $reserva->estacionamento) selected @endif>Sim</option>
                            <option value="0" @if(old('estacionamento')=="0" || !$reserva->estacionamento) selected @endif>Não</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-lg input-lg form-control">Editar</button>
                    </div>
                </form>

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
