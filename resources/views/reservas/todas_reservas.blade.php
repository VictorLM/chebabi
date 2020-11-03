@extends('intranet.templates.template')

@section('content')

    <div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2>
                  <form class="form-inline" method="GET" action="{{action('ReservasEstacoes\ReservasEstacoesController@todas_reservas')}}">
                    <label style="font-weight: normal;"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Reservas do dia </label>
                    <input type="date" name="date" class="form-control input-lg" value="{{ Carbon\Carbon::parse($date)->format('Y-m-d') }}" required>
                    <button type="submit" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
                  </form>
                </h2>
                <span>Total de vagas por dia - Estações: <b>{{$vagas_total_dia['estacoes']}}</b>, Estacionamento: <b>{{$vagas_total_dia['estacionamento']}}</b>.</span>
            </div>

            <div class="panel-body">

                @if($reservas->count() > 0)
                  <table class="table">

                    <thead>
                        <tr>
                            <th>Início</th>
                            <th>Fim</th>
                            <th>Estacionamento</th>
                            <th>Usuário</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                      
                      @foreach ($reservas as $reserva)

                        <tr>
                            <td>
                              {{ Carbon\Carbon::parse($reserva->inicio)->format('d/m/Y') }}
                            </td>
                            <td>
                              {{ Carbon\Carbon::parse($reserva->fim)->format('d/m/Y') }}
                            </td>
                            <td>
                              @if($reserva->estacionamento)
                                <i class="glyphicon glyphicon-ok-circle" title="Sim" style="color:green;font-size:1.5em;"></i>
                              @else
                                <i class="glyphicon glyphicon-remove-circle" title="Não" style="color:red;font-size:1.5em"></i>
                              @endif
                            </td>
                            <td>{{ $reserva->usuario->name }}</td>

                        </tr>

                      @endforeach

                    </tbody>

                  </table>
                @else

                  <h2 class="text-center">Não há reservas nesta data.</h2>

                @endif

            </div>

        </div>

    </div>

@endsection
