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
                    </div>

                    <div class="col-md-4">
                        <h4>Legenda de cores dos eventos:</h4>
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td class="td-cores-agenda" style="background-color:yellow;"><small><b>Ausente</b></small></td>
                                    <td class="td-cores-agenda" style="background-color:#00e600;"><small><b>Carro</b></small></td>
                                    <td class="td-cores-agenda" style="background-color:#80bfff;"><small><b>Reunião</b></small></td>
                                    <td class="td-cores-agenda" style="background-color:#3a87ad;"><small><b>Outro</b></small></td>
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
