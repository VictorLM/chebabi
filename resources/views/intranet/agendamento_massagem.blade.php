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
                Agendamento Massagem
                @if($link_todos_agendamentos)
                    <a href="{{url('/intranet/agendamento-massagem/todos-agendamentos')}}"><button type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-list"></i> Agendamentos</button></a>
                    <a href="{{url('/intranet/agendamento-massagem/incluir-dia-sem-massagem')}}"><button type="button" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-plus"></i> Dia sem massagem</button></a>
                @endif
            </h2>
            <small>
                <b>O limite de agendamentos é de 1 por dia e até 4 por mês.</b><br/>
                Cada sessão de massagem rápida dura 10 minutos. Só é possivél agendar uma sessão para a semana corrente. Os novos agendamentos são liberados toda segunda-feira.
            </small>
        </div>

        <div class="panel-body">

            @if (Session::has('alert-success'))
                <div class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <li>{{Session::get('alert-success')}}</li>
                </div>
            @elseif ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="text-center" style="margin:0;"><b>Terça-feira - {{Carbon\Carbon::parse($terca['dia'])->format('d/m/Y')}}</b></h2>
                        </div>
                            
                        <div class="panel-body">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Agendar</th>
                                    </tr>
                                </thead>
                                @if($terca['disponivel'] && $terca['dia'] >= Carbon\Carbon::today())
                                    @foreach($terca['horarios'] as $key => $horario)

                                        <tr class="text-center @if(!empty($horario) && $horario == Auth::user()->name) success @elseif(!empty($horario) || $terca['limite_usuario']) active @endif">
                                            <td><h4>{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes(10)->format('H:i')}}</h4></td>
                                            <td>
                                                @if(!empty($horario))
                                                    @if($horario == Auth::user()->name)
                                                        <span><b>Horário agendado por você.</b></span><br/>
                                                        @if($terca['dia'] > Carbon\Carbon::today())
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@cancelar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($terca['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">
    
                                                                <a class="cancelar-btn" data-link="{{Carbon\Carbon::parse($terca['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-danger">Cancelar</button>
                                                                </a>
                                                            </form>
                                                        @elseif($terca['dia'] == Carbon\Carbon::today() && $key > Carbon\Carbon::parse(Carbon\Carbon::now())->format('H:i'))
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@cancelar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($terca['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">
    
                                                                <a class="cancelar-btn" data-link="{{Carbon\Carbon::parse($terca['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-danger">Cancelar</button>
                                                                </a>
                                                            </form>
                                                        @else
                                                            <span><i>Data e/ou horário passados.</i></span>
                                                        @endif
                                                    @else
                                                        <span><b>Horário indisponivél.</b></span><br/>
                                                        <small><i>Agendado pelo usuário <b>{{$horario}}.</b></i></small>
                                                    @endif
                                                @else
                                                    @if(!$terca['limite_usuario'])
                                                        @if($terca['dia'] > Carbon\Carbon::today())
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@criar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($terca['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">
                                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                                </a>
                                                            </form>
                                                        @elseif($terca['dia'] == Carbon\Carbon::today() && $key > Carbon\Carbon::parse(Carbon\Carbon::now())->format('H:i'))
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@criar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($terca['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">
                                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                                </a>
                                                            </form>
                                                        @else
                                                            <span><i>Data e/ou horário passados.</i></span>
                                                        @endif
                                                    @else
                                                        <small><i>Já existe um agendamento realizado por você neste dia ou seu limite de agendamentos mensais foi atingido.</i></small>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                @elseif($terca['disponivel'] && $terca['dia'] < Carbon\Carbon::today())

                                    <tr class="active">
                                        <td colspan="2">
                                            <h1 class="text-center"><b>DATA INDISPONÍVEL</b></h1>
                                            <h3 class="text-justify">ESSA DATA JÁ PASSOU. VOLTE NA SEMANA QUE VEM PARA AGENDAR UMA NOVA SESSÃO.</h3>
                                        </td>
                                    </tr>

                                @else

                                    <tr class="active">
                                        <td colspan="2">
                                            <h1 class="text-center"><b>DATA INDISPONÍVEL</b></h1>
                                            <h3 class="text-justify">NÃO HAVERÁ SESSÕES DE MASSAGEM NESTE DIA.</h3>
                                        </td>
                                    </tr>

                                @endif

                            </table>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="text-center" style="margin:0;"><b>Quarta-feira - {{Carbon\Carbon::parse($quarta['dia'])->format('d/m/Y')}}</b></h2>
                        </div>
                            
                        <div class="panel-body">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Agendar</th>
                                    </tr>
                                </thead>
                                @if($quarta['disponivel'] && $quarta['dia'] >= Carbon\Carbon::today())
                                    @foreach($quarta['horarios'] as $key => $horario)

                                        <tr class="text-center @if(!empty($horario) && $horario == Auth::user()->name) success @elseif(!empty($horario) || $quarta['limite_usuario']) active @endif">
                                            <td><h4>{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes(10)->format('H:i')}}</h4></td>
                                            <td>
                                                @if(!empty($horario))
                                                    @if($horario == Auth::user()->name)
                                                        <span><b>Horário agendado por você.</b></span><br/>
                                                        @if($quarta['dia'] > Carbon\Carbon::today())
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@cancelar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($quarta['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">

                                                                <a class="cancelar-btn" data-link="{{Carbon\Carbon::parse($quarta['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-danger">Cancelar</button>
                                                                </a>
                                                            </form>
                                                        @elseif($quarta['dia'] == Carbon\Carbon::today() && $key > Carbon\Carbon::parse(Carbon\Carbon::now())->format('H:i'))
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@cancelar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($quarta['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">

                                                                <a class="cancelar-btn" data-link="{{Carbon\Carbon::parse($quarta['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-danger">Cancelar</button>
                                                                </a>
                                                            </form>
                                                        @else
                                                            <span><i>Data e/ou horário passados.</i></span>
                                                        @endif
                                                    @else
                                                        <span><b>Horário indisponivél.</b></span><br/>
                                                        <small><i>Agendado pelo usuário <b>{{$horario}}.</b></i></small>
                                                    @endif
                                                @else
                                                    @if(!$quarta['limite_usuario'])
                                                        @if($quarta['dia'] > Carbon\Carbon::today())
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@criar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($quarta['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">
                                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                                </a>
                                                            </form>
                                                        @elseif($quarta['dia'] == Carbon\Carbon::today() && $key > Carbon\Carbon::parse(Carbon\Carbon::now())->format('H:i'))
                                                            <form class="form-horizontal" method="POST" action="{{action('APIs\MicrosoftController@criar_evento_massagem')}}">
                                                                {{csrf_field()}}
                                                                
                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($quarta['dia'])->format('Y-m-d')}}">
                                                                <input name="hora" type="hidden" value="{{$key}}:00">
                                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                                </a>
                                                            </form>
                                                        @else
                                                            <span><i>Data e/ou horário passados.</i></span>
                                                        @endif
                                                    @else
                                                        <small><i>Já existe um agendamento realizado por você neste dia ou seu limite de agendamentos mensais foi atingido.</i></small>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                @elseif($quarta['disponivel'] && $quarta['dia'] < Carbon\Carbon::today())

                                    <tr class="active">
                                        <td colspan="2">
                                            <h1 class="text-center"><b>DATA INDISPONÍVEL</b></h1>
                                            <h3 class="text-justify">ESSA DATA JÁ PASSOU. VOLTE NA SEMANA QUE VEM PARA AGENDAR UMA NOVA SESSÃO.</h3>
                                        </td>
                                    </tr>

                                @else

                                    <tr class="active">
                                        <td colspan="2">
                                            <h1 class="text-center"><b>DATA INDISPONÍVEL</b></h1>
                                            <h3 class="text-justify">NÃO HAVERÁ SESSÕES DE MASSAGEM NESTE DIA.</h3>
                                        </td>
                                    </tr>

                                @endif

                            </table>
                            
                        </div>
                    </div>
                </div>

            </div>

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
    <script src="{{asset('assets/js/agendamento_massagem.js')}}"></script>
@endpush

@endsection


