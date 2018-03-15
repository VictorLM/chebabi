@extends('intranet.templates.template')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/fullcalendar.print.css" media="print"/>
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')

    <div class="container-fluid">

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="container-fluid" style="padding:0!important;">
                    <div class="col-md-8 left" style="padding:0!important;">
                        <h2>
                            <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Agenda
                            <a href="{{url('/intranet/agenda/novo-evento')}}">
                                <button type="button" class="btn btn-sm btn-primary">
                                    <i class="glyphicon glyphicon-plus"></i> Novo evento
                                </button>
                            </a>
                        </h2>  
                        <small>Os eventos são sincronizados a cada cinco minutos. São 
                        sincronizados os eventos datados de até três meses a partir da data atual.</small>
                    </div>

                    <div class="col-md-4">
                        <h4>Legenda de cores dos eventos:</h4>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td style="background-color:yellow;color:black;text-align:center;"><small><b>Ausente</b></small></td>
                                    <td style="background-color:#00e600;color:black;text-align:center;"><small><b>Carro</b></small></td>
                                    <td style="background-color:#ffa31a;color:black;text-align:center"><small><b>Motorista</b></small></td>
                                    <td style="background-color:#80bfff;color:black;text-align:center"><small><b>Reunião</b></small></td>
                                    <td style="background-color:#3a87ad;color:black;text-align:center"><small><b>Outro</b></small></td>
                                    <td style="background-color:red;color:black;text-align:center"><small><b>Cancelado</b></small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                
                <i id="spinner" class="fa fa-refresh fa-spin fa-3x fa-fw" 
                style="text-align: center; margin-left: 45%; margin-right: 45%;"></i>
                
                <div id='calendar'></div>

            </div>
        </div>

    </div>

@push ('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/locale/pt-br.js"></script>
    <script src="{{url("assets/js/agenda.js")}}"></script>
@endpush

@endsection
