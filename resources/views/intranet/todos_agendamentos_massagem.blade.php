@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet/agendamento-massagem')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Agendamentos massagens
                <a href="{{url('/intranet/agendamento-massagem/incluir-dia-sem-massagem')}}">
                    <button type="button" class="btn btn-sm btn-info">
                        <i class="glyphicon glyphicon-plus"></i> Dia sem massagem
                    </button>
                </a>
            </h2>
            <small>
                Listando somente os agendamentos futuros.<br/>
                <b>Somente os Administradores e a Recepção tem acesso a esta tela.</b>
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

                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="text-center"><b>Agendamentos massagens</b></h2>
                        </div>
                            
                        <div class="panel-body">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Data</th>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Usuário</th>
                                        <th class="text-center">Data agendamento</th>
                                    </tr>
                                </thead>
                                
                                    @foreach($massagens_agendadas as $massagem_agendada)
                                        <tr class="text-center">
                                            <td>
                                                {{Carbon\Carbon::parse($massagem_agendada->inicio_data)->format('d/m/Y')}}
                                                @if(Carbon\Carbon::parse($massagem_agendada->inicio_data)->dayOfWeek == 2)
                                                    - Terça-feira
                                                @elseif(Carbon\Carbon::parse($massagem_agendada->inicio_data)->dayOfWeek == 3)
                                                    - Quarta-feira
                                                @endif
                                            </td>
                                            <td>{{Carbon\Carbon::parse($massagem_agendada->inicio_hora)->format('H:i')}} às {{Carbon\Carbon::parse($massagem_agendada->fim_hora)->format('H:i')}}</td>
                                            <td>{{$massagem_agendada->user->name}}</td>
                                            <td>{{Carbon\Carbon::parse($massagem_agendada->created_at)->format('d/m/Y H:i')}}</td>
                                        </tr>
                                    @endforeach

                            </table>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="text-center"><b>Dias sem massagem</b></h2>
                        </div>
                            
                        <div class="panel-body">

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Data</th>
                                        <th class="text-center">Data inclusão</th>
                                    </tr>
                                </thead>
                                
                                    @foreach($dias_sem_massagem as $dia_sem_massagem)
                                        <tr class="text-center">
                                            <td>{{Carbon\Carbon::parse($dia_sem_massagem->data)->format('d/m/Y')}}</td>
                                            <td>{{Carbon\Carbon::parse($dia_sem_massagem->created_at)->format('d/m/Y H:i')}}</td>
                                        </tr>
                                    @endforeach

                            </table>
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


@endsection


