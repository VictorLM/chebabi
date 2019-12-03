@extends('intranet.templates.template')

@push('meta')
    <meta http-equiv="refresh" content="120"/>
@endpush

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet/terapias')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Painel do Administrador
            </h2>
            <small>
                <b>Somente os Administradores e a Recepção tem acesso a este painel.</b>
            </small>
        </div>

        <div class="panel-body">

            @if (Session::has('alert-success'))
                <div class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <li>{{ Session::get('alert-success') }}</li>
                </div>
            @elseif ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                    
                <div class="col-md-3">
                    <div class="terapias-link intra-atalhos well well-lg">
                        <h2><a href="#" id="agendamentos" data-toggle="popover" data-trigger="focus" data-content="#">SESSÕES<br/>DO DIA</a></h2>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="terapias-link intra-atalhos well well-lg">
                        <h2><a href="#" data-toggle="modal" data-target="#diasSemTerapiasModal">DIAS SEM<br/>TERAPIAS</a></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="terapias-link intra-atalhos well well-lg">
                        <h2><a href="#" data-toggle="modal" data-target="#graficosModal">GRÁFICOS<br/>TERAPIAS</a></h2>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Modal Quick Massage -->
    <div class="modal fade" id="quickModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center font-dosis header-padding-0">
                        <b>QUICK MASSAGE - {{Carbon\Carbon::today()->format("d/m/Y")}}</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </h1>
                </div>
                <div class="modal-body text-justify">

                    <div class="row">
                        <div class="col-md-12" style="text-align:center;">

                            <form class="form-inline" id="form-qs-email" method="POST" action="{{action('Terapias\TerapiasController@enviar_lista_sessoes_dia_email')}}">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="email" class="form-control {{$errors->has('email') ? ' has-error' : ''}}" name="email" value="{{old('email')}}" required placeholder="nome@email.com">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('email')}}</strong>
                                        </span>
                                    @endif
                                </div>
                                <input name="tipo" type="hidden" value="qm">
                                <button type="submit" class="btn btn-primary">Enviar por e-mail</button>
                            </form>
                            <br/>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Usuário</th>
                                    </tr>
                                </thead>
                                @if(count($quick_massage_array['dias']) < 1 || !$quick_massage_array['dias'][0]["disponivel"])
                                    <tr class="active">
                                        <td colspan="3">
                                            <h4 class="text-center">Não haverá sessões neste dia.</h3>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($quick_massage_array['dias'][0]['horarios'] as $key => $horario)
                                        <tr class="text-center">
                                            <td>{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes($quick_massage_array['tempo_sessao'])->format('H:i')}}</td>
                                            <td>
                                                @if($horario) {{-- se horário ocupado --}}
                                                    <i>Agendado por <b>{{$horario}}</b></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Quick Massage -->

    <!-- Modal Auriculoterapia -->
    <div class="modal fade" id="auriculoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center font-dosis header-padding-0">
                        <b>AURICULOTERAPIA - {{Carbon\Carbon::today()->format("d/m/Y")}}</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </h1>
                </div>
                <div class="modal-body text-justify">

                    <div class="row">
                        <div class="col-md-12" style="text-align:center;">

                            <form class="form-inline" id="form-at-email" method="POST" action="{{action('Terapias\TerapiasController@enviar_lista_sessoes_dia_email')}}">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="email" class="form-control {{$errors->has('email') ? ' has-error' : ''}}" name="email" value="{{old('email')}}" required placeholder="nome@email.com">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('email')}}</strong>
                                        </span>
                                    @endif
                                </div>
                                <input name="tipo" type="hidden" value="at">
                                <button type="submit" class="btn btn-primary">Enviar por e-mail</button>
                            </form>
                            <br/>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Usuário</th>
                                    </tr>
                                </thead>
                                @if(count($auriculoterapia_array['dias']) < 1 || !$auriculoterapia_array['dias'][0]["disponivel"])
                                    <tr class="active">
                                        <td colspan="3">
                                            <h4 class="text-center">Não haverá sessões neste dia.</h3>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($auriculoterapia_array['dias'][0]['horarios'] as $key => $horario)
                                        <tr class="text-center">
                                            <td>{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes($auriculoterapia_array['tempo_sessao'])->format('H:i')}}</td>
                                            <td>
                                                @if($horario) {{-- se horário ocupado --}}
                                                    <i>Agendado por <b>{{$horario}}</b></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Auriculoterapia -->

    <!-- Modal Massagem nos Pés -->
    <div class="modal fade" id="pesModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center font-dosis header-padding-0">
                        <b>MASSAGEM NOS PÉS - {{Carbon\Carbon::today()->format("d/m/Y")}}</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </h1>
                </div>
                <div class="modal-body text-justify">

                    <div class="row">
                        <div class="col-md-12" style="text-align:center;">

                            <form class="form-inline" id="form-mp-email" method="POST" action="{{action('Terapias\TerapiasController@enviar_lista_sessoes_dia_email')}}">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="email" class="form-control {{$errors->has('email') ? ' has-error' : ''}}" name="email" value="{{old('email')}}" required placeholder="nome@email.com">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('email')}}</strong>
                                        </span>
                                    @endif
                                </div>
                                <input name="tipo" type="hidden" value="mp">
                                <button type="submit" class="btn btn-primary">Enviar por e-mail</button>
                            </form>
                            <br/>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Usuário</th>
                                    </tr>
                                </thead>
                                @if(count($massagem_pes_array['dias']) < 1 || !$massagem_pes_array['dias'][0]["disponivel"])
                                    <tr class="active">
                                        <td colspan="3">
                                            <h4 class="text-center">Não haverá sessões neste dia.</h3>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($massagem_pes_array['dias'][0]['horarios'] as $key => $horario)
                                        <tr class="text-center">
                                            <td>{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes($massagem_pes_array['tempo_sessao'])->format('H:i')}}</td>
                                            <td>
                                                @if($horario) {{-- se horário ocupado --}}
                                                    <i>Agendado por <b>{{$horario}}</b></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Massagem nos Pés -->

    <!-- Modal Dias sem terapias -->
    <div class="modal fade" id="diasSemTerapiasModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center font-dosis header-padding-0">
                        <b>DIAS SEM TERAPIAS - {{Carbon\Carbon::today()->format("m/Y")}}</b>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </h1>
                </div>
                <div class="modal-body text-justify">

                    <div class="row">
                        <div class="col-md-12" style="text-align:center;">

                            <form class="form-inline" method="POST" action="{{action('Terapias\TerapiasController@incluir_dia_sem_terapia')}}">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="date" class="form-control {{$errors->has('data') ? ' has-error' : ''}}" name="data" value="{{old('data')}}" required>
                                    @if ($errors->has('data'))
                                        <span class="help-block">
                                            <strong>{{$errors->first('data')}}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="tipo">
                                        <option>Tipo</option>
                                        <option value="quick_massages">Quick Massage</option>
                                        <option value="auriculoterapias">Auriculoterapia</option>
                                        <option value="massagens_pes">Massagem nos Pés</option>
                                        <option value="massagens_relaxantes">Massagem Relaxante</option>
                                        <option value="mat_pilates">MAT Pilates</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Incluir</button>
                            </form>
                            <br/>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Dia</th>
                                        <th class="text-center">Tipo</th>
                                        <th class="text-center">Usuário</th>
                                        <th class="text-center">Cadastrado</th>
                                    </tr>
                                </thead>
                                @foreach($dias_sem_terapias as $dia)
                                    <tr class="text-center">
                                        <td>{{Carbon\Carbon::parse($dia->data)->format('d/m/Y')}}</td>
                                        <td>{{$dia->tipo}}</td>
                                        <td>{{$dia->name}}</td>
                                        <td>{{Carbon\Carbon::parse($dia->created_at)->format('d/m/Y H:i')}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Dias sem terapias -->

    <!-- Modal Dias Gráficos -->
    <div class="modal fade" id="graficosModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>                        
                    <form class="form-inline text-center" id="form">
                        {{ csrf_field() }}
                        <h1 class="inline-header font-dosis header-padding-0"><label class="header-padding-0"><b> GRÁFICOS TERAPIAS - </b></label></h1>
                        <div class="form-group" style="margin-bottom:1em;">
                            <select class="form-control" id="mes">
                                <option value="1" @if(Carbon\Carbon::today()->format("m") == 1) selected @endif>Janeiro</option>
                                <option value="2" @if(Carbon\Carbon::today()->format("m") == 2) selected @endif>Fevereiro</option>
                                <option value="3" @if(Carbon\Carbon::today()->format("m") == 3) selected @endif>Março</option>
                                <option value="4" @if(Carbon\Carbon::today()->format("m") == 4) selected @endif>Abril</option>
                                <option value="5" @if(Carbon\Carbon::today()->format("m") == 5) selected @endif>Maio</option>
                                <option value="6" @if(Carbon\Carbon::today()->format("m") == 6) selected @endif>Junho</option>
                                <option value="7" @if(Carbon\Carbon::today()->format("m") == 7) selected @endif>Julho</option>
                                <option value="8" @if(Carbon\Carbon::today()->format("m") == 8) selected @endif>Agosto</option>
                                <option value="9" @if(Carbon\Carbon::today()->format("m") == 9) selected @endif>Setembro</option>
                                <option value="10" @if(Carbon\Carbon::today()->format("m") == 10) selected @endif>Outubro</option>
                                <option value="11" @if(Carbon\Carbon::today()->format("m") == 11) selected @endif>Novembro</option>
                                <option value="12" @if(Carbon\Carbon::today()->format("m") == 12) selected @endif>Dezembro</option>
                            </select>
                        </div>
                        <h1 class="inline-header font-dosis header-padding-0">/</h1>
                        <div class="form-group" style="margin-bottom:1em;">
                            <input type="number" class="form-control" id="ano" value="{{$request->mes ?? Carbon\Carbon::today()->format("Y")}}">
                        </div>
                        <button type="button" id="filtrar-terapias-charts" class="btn btn-success" style="margin-bottom:1em;"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>                           
                    </form>

                </div>
                <div class="modal-body text-justify modal-body-padding-10">

                    <div class="row">

                        <div class="col-md-6">
                            <h3 class="text-center margin-0">Resumo terapias</h3>
                            <div id="chart_div_resumo_terapias"></div>
                        </div>

                        <div class="col-md-6">
                            <h3 class="text-center margin-0">Sessões por usuário - Top 10</h3>
                            <div id="chart_div_agendamentos_por_usuario"></div>
                        </div>

                    </div>
                    <hr/>
                    <div class="row">

                        <div class="col-md-12">
                            <h3 class="text-center margin-0">Sessões por usuário e por tipo terapia - Top 15</h3>
                            <div id="chart_div_sessoes_por_usuario_e_por_tipo_terapia"></div>
                        </div>

                    </div>
                    {{--
                    <div class="row">

                        <div class="col-md-6">
                            <h3 class="text-center margin-0">Sessões não agendadas</h3>
                            <div id="chart_div_sessoes_livres_por_terapia"></div>
                        </div>

                    </div>
                    --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Gráficos -->


</div>

@push ('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{asset('assets/js/terapias_painel_admin.js')}}"></script>
@endpush


@endsection


