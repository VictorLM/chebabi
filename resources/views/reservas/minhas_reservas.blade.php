@extends('intranet.templates.template')

@section('content')

    <div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2>
                    <a href="{{ url('/intranet') }}"><i class="glyphicon glyphicon-arrow-left"></i></a>
                    Minhas Reservas
                </h2>
            </div>

            <div class="panel-body">

                {{-- Alertas flash-messages --}}
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <li>{{ $error }}</li>
                        </div>
                    @endforeach
                @endif

                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                  @if(Session::has('alert-' . $msg))
                    <div class="alert alert-{{$msg}} alert-dismissable">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      {{ Session::get('alert-' . $msg) }}
                    </div>
                  @endif
                @endforeach

                @if($reservas_user->count() > 0)
                  <table class="table">

                    <thead>
                        <tr>
                            <th><h3>Início</h3></th>
                            <th><h3>Fim</h3></th>
                            <th><h3>Estacionamento</h3></th>
                            <th><h3>Ações</h3></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                      
                      @foreach ($reservas_user as $reserva)

                        <tr>
                            <td>
                              <h4>{{ Carbon\Carbon::parse($reserva->inicio)->format('d/m/Y') }}</h4>
                            </td>
                            <td>
                              <h4>{{ Carbon\Carbon::parse($reserva->fim)->format('d/m/Y') }}</h4>
                            </td>
                            <td>
                              @if($reserva->estacionamento)
                                <i class="glyphicon glyphicon-ok-circle" title="Sim" style="color:green;font-size:1.7em;margin-top:.1em;"></i>
                              @else
                                <i class="glyphicon glyphicon-remove-circle" title="Não" style="color:red;font-size:1.7em;margin-top:.1em;"></i>
                              @endif
                            </td>
                            <td>
                              {{--
                              <a href="{{action('ReservasEstacoes\ReservasEstacoesController@editar_form', $reserva->id)}}">
                                <button class="btn btn-primary" type="button" title="Editar" style="width: 100px; margin-right: 5px;">
                                    <i class="glyphicon glyphicon-pencil"></i> Editar
                                </button>
                              </a>
                              --}}
                              <a href="{{action('ReservasEstacoes\ReservasEstacoesController@cancelar', $reserva->id)}}"  onclick="return confirm('Tem certeza que deseja cancelar essa reserva?')">
                                <button class="btn btn-danger" type="button" title="Cancelar">
                                    <i class="glyphicon glyphicon-remove"></i> Cancelar
                                </button>
                              </a>
                            </td>
                        </tr>

                      @endforeach

                    </tbody>

                  </table>
                @else

                  <h2 class="text-center">Não há reservas futuras em seu nome. <a href='/intranet/reservar'>Nova Reserva</a>.</h2>

                @endif

            </div>

        </div>

    </div>

@endsection
