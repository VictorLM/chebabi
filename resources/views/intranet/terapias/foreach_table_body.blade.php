@if(!$dia["disponivel"]) {{-- se dia sem terapia --}}

    <tr class="active">
        <td colspan="2">
            <h4 class="text-center">Não haverá sessões neste dia.</h3>
        </td>
    </tr>

@else {{-- senão dia sem terapia --}}

    @foreach($dia['horarios'] as $key => $horario)

        @php
            $active_class = null;
            $horario || Carbon\Carbon::parse($dia['dia']." ".$key)->isPast() || $dia['limite_mensal'] || $dia['limite_diario'] || $dia['limite_intervalo_agendamento'] ? $active_class = "active" : "";
            ////
            $action = null;
            if($terapia['tipo'] == "quick_massages"){
                $action = "quick_massage";
            }else if($terapia['tipo'] == "auriculoterapias"){
                $action = "auriculoterapia";
            }else if($terapia['tipo'] == "massagens_pes"){
                $action = "massagem_pes";
            }
        @endphp

        <tr class="text-center {{$active_class}}">
            <td width="40%">{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes($terapia['tempo_sessao'])->format('H:i')}}</td>
            <td width="60%" style="padding:0;padding-top:3px;">

                @if($horario) {{-- se horário ocupado --}}

                    @if($horario == Auth::user()->name)

                        @if(Carbon\Carbon::parse($dia['dia']." ".$key)->isPast()) {{-- se passado --}}
                            <i><small>Agendado por <b>{{$horario}}</b></small></i>
                        @else {{-- senão passado --}}

                            <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@cancelar_'.$action)}}">
                                {{csrf_field()}}
                                <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                <input name="hora" type="hidden" value="{{$key}}:00">
                                <a class="cancelar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                    <button type="submit" class="btn btn-sm btn-danger" style="width:100%;">Agendado por você. Cancelar</button>
                                </a>
                            </form>

                        @endif

                    @else
                        <i><small>Agendado por <b>{{$horario}}</b></small></i>
                    @endif

                @else {{-- se horário livre --}}

                    @if(Carbon\Carbon::parse($dia['dia']." ".$key)->isPast()) {{-- se passado --}}
                        <i><small>Essa sessão já passou.</small></i>  
                    @else {{-- senão passado --}}

                        {{-- IF AURICULOTERAPIA --}}
                        @if($terapia['tipo'] == "auriculoterapias") {{-- se auroculoterapia, não permitir bonus dentro no limite agendamento de 7 dias --}}

                            @if($dia['limite_diario']) {{-- se já bateu limite diário (outras terapias incluso) --}}

                                <i><small>Você já tem uma sessão de <b>{{$dia['limite_diario']}}</b> agendada nesse dia.</small></i>

                            @elseif($dia['limite_intervalo_agendamento']) {{-- se limite min. $terapia['limite_intervalo_agendamento'] dias. Auticulo são 7 dias --}}
                                
                                <i><small>Só é possível agendar uma sessão desta terapia a cada {{$terapia['intervalo_agendamento']}} dias.</small></i>

                            @elseif($dia['limite_mensal']) {{-- se já bateu limite mensal --}}

                                @if(Carbon\Carbon::now()->between(Carbon\Carbon::parse($dia['dia']." ".$key)->subMinutes($limite_livre_bonus), Carbon\Carbon::parse($dia['dia']." ".$key))) {{-- se ainda livre 30 min antes do início --}}
                                    <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@agendar_'.$action)}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                        <input name="hora" type="hidden" value="{{$key}}:00">
                                        <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                            <button type="submit" class="btn btn-sm btn-success" style="width:100%;">Agendar bônus</button>
                                        </a>
                                    </form>
                                @else
                                    <i><small>Você já atingiu o limite mensal de agendamentos p/ essa terapia.</small></i>
                                @endif

                            @else

                                <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@agendar_'.$action)}}">
                                    {{csrf_field()}}
                                    <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                    <input name="hora" type="hidden" value="{{$key}}:00">
                                    <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                        <button type="submit" class="btn btn-sm btn-success" style="width:100%;">Agendar</button>
                                    </a>
                                </form>

                            @endif

                        @else {{-- SENÃO AURICULOTERAPIA --}}

                            @if($dia['limite_diario']) {{-- se já bateu limite diário (outras terapias incluso) --}}

                                <i><small>Você já tem uma sessão de <b>{{$dia['limite_diario']}}</b> agendada nesse dia.</small></i>

                            @elseif($dia['limite_mensal'] || $dia['limite_intervalo_agendamento']) {{-- se já bateu limite mensal ou intervalo agendamento --}}

                                @if(Carbon\Carbon::now()->between(Carbon\Carbon::parse($dia['dia']." ".$key)->subMinutes($limite_livre_bonus), Carbon\Carbon::parse($dia['dia']." ".$key))) {{-- se ainda livre 30 min antes do início --}}
                                    <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@agendar_'.$action)}}">
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
                                    @elseif($dia['limite_intervalo_agendamento'])
                                        <i><small>Você já atingiu o limite semanal de agendamentos p/ essa terapia.</small></i>
                                    @endif
                                @endif

                            @else

                                <form class="form-horizontal" method="POST" action="{{action('Terapias\TerapiasController@agendar_'.$action)}}">
                                    {{csrf_field()}}
                                    <input name="data" type="hidden" value="{{Carbon\Carbon::parse($dia['dia'])->format('Y-m-d')}}">
                                    <input name="hora" type="hidden" value="{{$key}}:00">
                                    <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}} às {{$key}}">
                                        <button type="submit" class="btn btn-sm btn-success" style="width:100%;">Agendar</button>
                                    </a>
                                </form>

                            @endif

                        @endif
                        {{-- FIM IF AURICULOTERAPIA --}}

                    @endif

                @endif

            </td>
        </tr>

    @endforeach

@endif