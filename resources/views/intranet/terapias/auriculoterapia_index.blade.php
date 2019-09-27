@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@push('meta')
    <meta http-equiv="refresh" content="60"/>
@endpush

@section('content')

<div class="container-index">
    {{-- Alertas flash-messages --}}
    @if (Session::has('alert-success'))
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <li>{{Session::get('alert-success')}}</li>
        </div>
    @elseif ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <li>{{$error}}</li>
            </div>
        @endforeach
    @endif

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet/terapias')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Agendamento <b>Auriculoterapia</b>
            </h2>
            <small>
                São {{$terapia['tempo_sessao']}} minutos por sessão. Saiba mais sobre Auriculoterapia <a href="https://pt.wikipedia.org/wiki/Auriculoterapia" target="_blank">aqui</a>. Sessões toda segunda, terça, quinta e sexta-feira.<br/>
                <b>O limite de agendamentos por usuário é de 1 sessão a cada sete dias e até {{$terapia['limite_mensal']}} por mês.</b>
            </small>
        </div>

        <div class="panel-body">

            <div class="row">
                @foreach(array_chunk($auriculoterapia_array['dias'], 2) as $chunk_dias)

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">

                        @foreach($chunk_dias as $key => $dia)

                                <div class="box">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="text-center" style="margin:0;"><b>{{$dia['dia_feira']}} - {{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}}</b></h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered">

                                                @if(!$dia["disponivel"])

                                                    <tr class="active">
                                                        <td colspan="2">
                                                            <h4 class="text-center">Não haverá sessões neste dia.</h3>
                                                        </td>
                                                    </tr>

                                                @else

                                                    @foreach($dia['horarios'] as $key => $horario)

                                                        @php
                                                            $active_class = null;
                                                            $horario || Carbon\Carbon::parse($dia['dia']." ".$key)->isPast() || $dia['limite_mensal'] || $dia['limite_diario'] || $dia['limite_intervalo_agendamento'] ? $active_class = "active" : "";                                                            
                                                        @endphp

                                                        <tr class="text-center {{$active_class}}">
                                                            <td width="40%">{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes($terapia['tempo_sessao'])->format('H:i')}}</td>
                                                            <td width="60%" style="padding:0;padding-top:3px;">

                                                                @if($horario) {{-- se horário ocupado --}}

                                                                    @if($horario == Auth::user()->name)
                                                                        <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@cancelar_auriculoterapia')}}">
                                                                            {{csrf_field()}}
                                                                            <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                                                            <input name="hora" type="hidden" value="{{$key}}:00">
                                                                            <a class="cancelar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                                <button type="submit" class="btn btn-sm btn-danger" style="width:100%;">Agendado por você. Cancelar</button>
                                                                            </a>
                                                                        </form>
                                                                    @else
                                                                        <i><small>Agendado por <b>{{$horario}}</b></small></i>
                                                                    @endif

                                                                @else {{-- se horário livre --}}
                                                                    
                                                                    @if(Carbon\Carbon::parse($dia['dia']." ".$key)->isPast()) {{-- se passado --}}
                                                                        <i><small>Essa sessão já passou.</small></i>  
                                                                    @else {{-- senão passado --}}

                                                                        @if($dia['limite_intervalo_agendamento']) {{-- se limite min. $terapia['limite_intervalo_agendamento'] dias --}}
                                                                            <i><small>Só é possível agendar uma sessão desta terapia a cada {{$terapia['intervalo_agendamento']}} dias.</small></i>
                                                                        @elseif($dia['limite_mensal'] || $dia['limite_diario']) {{-- se já bateu limite mensal, diário (outras terapias incluso) ou intervalo agendamento --}}

                                                                            @if(Carbon\Carbon::now()->between(Carbon\Carbon::parse($dia['dia']." ".$key)->subMinutes($limite_livre_bonus), Carbon\Carbon::parse($dia['dia']." ".$key))) {{-- se ainda livre 30 min antes do início --}}
                                                                                <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@agendar_auriculoterapia')}}">
                                                                                    {{csrf_field()}}
                                                                                    <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                                                                    <input name="hora" type="hidden" value="{{$key}}:00">
                                                                                    <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                                        <button type="submit" class="btn btn-sm btn-success" style="width:100%;">Agendar bônus</button>
                                                                                    </a>
                                                                                </form>
                                                                            @else
                                                                                @if($dia['limite_mensal'])
                                                                                    <i><small>Você já atingiu o limite mensal de agendamentos p/ essa terapia.</small></i>
                                                                                @elseif($dia['limite_diario'])
                                                                                    <i><small>Você já tem uma sessão de <b>{{$dia['limite_diario']}}</b> agendada nesse dia.</small></i> 
                                                                                @endif
                                                                            @endif

                                                                        @else

                                                                            <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@agendar_auriculoterapia')}}">
                                                                                {{csrf_field()}}
                                                                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                                                                <input name="hora" type="hidden" value="{{$key}}:00">
                                                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                                                                    <button type="submit" class="btn btn-sm btn-success" style="width:100%;">Agendar</button>
                                                                                </a>
                                                                            </form>

                                                                        @endif

                                                                    @endif

                                                                @endif
                                                                
                                                            </td>
                                                        </tr>

                                                    @endforeach

                                                @endif

                                            </table>
                                        </div>
                                    </div>
                                </div>

                        @endforeach

                    </div>

                @endforeach

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
    <script src="{{asset('assets/js/agendamento_terapias.js')}}"></script>
@endpush

@endsection


