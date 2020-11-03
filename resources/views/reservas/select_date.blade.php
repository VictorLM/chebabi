@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Reservar Estação de Trabalho - Campinas
            </h2>
            <small>
                Antes de ir ao escritório, reserve sua estação de trabalho para respeitar as medidas de distanciamento social.
            </small>
        </div>

        <div class="panel-body text-center">

            {{-- Alertas flash-messages --}}
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissable text-left">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <li>{{$error}}</li>
                    </div>
                @endforeach
            @endif

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    
                    <h2>Selecione a data da reserva</h2>
                    <form method="GET" action="{{action('ReservasEstacoes\ReservasEstacoesController@index')}}">
                        <div class="input-group input-group-lg @if($errors->has('date')) has-error @endif">
                            <input type="date" name="date" class="form-control" value="{{old("date")}}" required>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Selecionar</button>
                            </span>
                        </div>
                    </form>

                    <h4>Ou <a href="{{url('/intranet/reservar/minhas-reservas')}}">ver minhas reservas</a></h4>
                    @if(Auth::user()->is_admin_recepcao())
                        <h4>Ou ainda</h4> 
                        <h4><a href="{{url('/intranet/reservar/reservas')}}">Ver todas as reservas</a></h4>
                    @endif
                </div>
            </div>

            <br/>
        </div>

    </div>
  
</div>

@endsection
