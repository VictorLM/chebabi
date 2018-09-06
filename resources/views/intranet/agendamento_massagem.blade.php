@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Agendamento Massagem
            </h2>
            <small>Cada sessão de massagem rápida dura 10 minutos. Só é possivél agendar uma sessão para a semana corrente. Os novos agendamentos são liberados toda segunda-feira.</small>
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

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        <h2 class="text-center" style="margin:0;"><b>Terça-feira - {{Carbon\Carbon::parse($terca)->format('d/m/Y')}}</b></h2>
                        </div>
                            
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Agendar</th>
                                    </tr>
                                </thead>
                                <tr class="text-center @if(isset($_1400) && !empty($_1400)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>14h00 às 14h10</h4></td>
                                        <td>
                                            @if(isset($_1400)  && !empty($_1400))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1400}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                {{--<tr class="text-center @if(isset($_1410) && !empty($_1410)) active @endif">--}}
                                <tr class="text-center active">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:10">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>14h10 às 14h20</h4></td>
                                        <td>
                                            <small><i>Horário indisponivél. <br/>Reservado pelo usuário Victor Meireles.</i></small>
                                            {{--
                                            @if(isset($_1410)  && !empty($_1410))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1410}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h10">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                            --}}
                                            
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1420) && !empty($_1420)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:20">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>14h20 às 14h30</h4></td>
                                        <td>
                                            @if(isset($_1420)  && !empty($_1420))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1420}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h20">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1430) && !empty($_1430)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:30">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>14h30 às 14h40</h4></td>
                                        <td>
                                            @if(isset($_1430)  && !empty($_1430))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1430}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h30">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                {{--<tr class="text-center @if(isset($_1440) && !empty($_1440)) active @endif">--}}
                                <tr class="text-center active">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:40">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>14h40 às 14h50</h4></small></td>
                                        <td>
                                            <small><i>Horário agendado por você.</i><br/>
                                            <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h40">
                                                <button type="submit" class="btn btn-danger">Cancelar</button>
                                            </a>
                                            {{--
                                            @if(isset($_1440)  && !empty($_1440))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1440}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h40">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                            --}}
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1450) && !empty($_1450)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:50">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>14h50 às 15h00</h4></td>
                                        <td>
                                            @if(isset($_1450)  && !empty($_1450))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1450}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 14h50">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1500) && !empty($_1500)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>15h00 às 15h10</h4></td>
                                        <td>
                                            @if(isset($_1500)  && !empty($_1500))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1500}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 15h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1510) && !empty($_1510)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:10">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>15h10 às 15h20</h4></td>
                                        <td>
                                            @if(isset($_1510)  && !empty($_1510))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1510}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 15h10">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1520) && !empty($_1520)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:20">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>15h20 às 15h30</h4></td>
                                        <td>
                                            @if(isset($_1520)  && !empty($_1520))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1520}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 15h20">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1540) && !empty($_1540)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:40">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>15h40 às 15h50</h4></td>
                                        <td>
                                            @if(isset($_1540)  && !empty($_1540))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1540}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 15h40">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1550) && !empty($_1550)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:50">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>15h50 às 16h00</h4></td>
                                        <td>
                                            @if(isset($_1550)  && !empty($_1550))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1550}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 15h50">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1600) && !empty($_1600)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>16h00 às 16h10</h4></td>
                                        <td>
                                            @if(isset($_1600)  && !empty($_1600))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1600}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 16h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1610) && !empty($_1610)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:10">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>16h10 às 16h20</h4></td>
                                        <td>
                                            @if(isset($_1610)  && !empty($_1610))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1610}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 16h10">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1620) && !empty($_1620)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:20">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>16h20 às 16h30</h4></td>
                                        <td>
                                            @if(isset($_1620)  && !empty($_1620))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1620}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 16h20">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1630) && !empty($_1630)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:30">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>16h30 às 16h40</h4></td>
                                        <td>
                                            @if(isset($_1630)  && !empty($_1630))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1630}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 16h30">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1640) && !empty($_1640)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:40">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>16h40 às 16h50</h4></td>
                                        <td>
                                            @if(isset($_1640)  && !empty($_1640))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1640}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 16h40">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1700) && !empty($_1700)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="17:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($terca)->format('Y-m-d')}}">

                                        <td><h4>17h00 às 17h10</h4></td>
                                        <td>
                                            @if(isset($_1700)  && !empty($_1700))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1700}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($terca)->format('d/m/Y')}} às 17h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="text-center" style="margin:0;"><b>Quarta-feira - {{Carbon\Carbon::parse($quarta)->format('d/m/Y')}}</b></h2>
                        </div>
                            
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Horário</th>
                                        <th class="text-center">Agendar</th>
                                    </tr>
                                </thead>
                                <tr class="text-center @if(isset($_1400) && !empty($_1400)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>14h00 às 14h10</h4></td>
                                        <td>
                                            @if(isset($_1400)  && !empty($_1400))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1400}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 14h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1410) && !empty($_1410)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:10">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>14h10 às 14h20</h4></td>
                                        <td>
                                            @if(isset($_1410)  && !empty($_1410))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1410}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 14h10">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1420) && !empty($_1420)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:20">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>14h20 às 14h30</h4></td>
                                        <td>
                                            @if(isset($_1420)  && !empty($_1420))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1420}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 14h20">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1430) && !empty($_1430)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:30">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>14h30 às 14h40</h4></td>
                                        <td>
                                            @if(isset($_1430)  && !empty($_1430))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1430}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 14h30">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1440) && !empty($_1440)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:40">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>14h40 às 14h50</h4></td>
                                        <td>
                                            @if(isset($_1440)  && !empty($_1440))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1440}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 14h40">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1450) && !empty($_1450)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="14:50">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>14h50 às 15h00</h4></td>
                                        <td>
                                            @if(isset($_1450)  && !empty($_1450))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1450}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 14h50">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1500) && !empty($_1500)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>15h00 às 15h10</h4></td>
                                        <td>
                                            @if(isset($_1500)  && !empty($_1500))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1500}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 15h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1510) && !empty($_1510)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:10">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>15h10 às 15h20</h4></td>
                                        <td>
                                            @if(isset($_1510)  && !empty($_1510))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1510}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 15h10">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1520) && !empty($_1520)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:20">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>15h20 às 15h30</h4></td>
                                        <td>
                                            @if(isset($_1520)  && !empty($_1520))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1520}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 15h20">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1540) && !empty($_1540)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:40">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>15h40 às 15h50</h4></td>
                                        <td>
                                            @if(isset($_1540)  && !empty($_1540))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1540}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 15h40">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1550) && !empty($_1550)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="15:50">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>15h50 às 16h00</h4></td>
                                        <td>
                                            @if(isset($_1550)  && !empty($_1550))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1550}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 15h50">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1600) && !empty($_1600)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>16h00 às 16h10</h4></td>
                                        <td>
                                            @if(isset($_1600)  && !empty($_1600))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1600}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 16h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1610) && !empty($_1610)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:10">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>16h10 às 16h20</h4></td>
                                        <td>
                                            @if(isset($_1610)  && !empty($_1610))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1610}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 16h10">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1620) && !empty($_1620)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:20">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>16h20 às 16h30</h4></td>
                                        <td>
                                            @if(isset($_1620)  && !empty($_1620))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1620}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 16h20">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1630) && !empty($_1630)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:30">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>16h30 às 16h40</h4></td>
                                        <td>
                                            @if(isset($_1630)  && !empty($_1630))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1630}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 16h30">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1640) && !empty($_1640)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="16:40">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>16h40 às 16h50</h4></td>
                                        <td>
                                            @if(isset($_1640)  && !empty($_1640))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1640}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 16h40">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                <tr class="text-center @if(isset($_1700) && !empty($_1700)) active @endif">
                                    <form class="form-horizontal" method="POST" action="{{action('Intranet\IntranetController@agendar_massagem')}}">
                                        {{csrf_field()}}
                                        <input name="data" type="hidden" value="17:00">
                                        <input name="hora" type="hidden" value="{{Carbon\Carbon::parse($quarta)->format('Y-m-d')}}">

                                        <td><h4>17h00 às 17h10</h4></td>
                                        <td>
                                            @if(isset($_1700)  && !empty($_1700))
                                                <small>Horário indisponivél. Reservado pelo usuário {{$_1700}}.</small>
                                            @else
                                                <a class="agendar-btn" data-link="{{Carbon\Carbon::parse($quarta)->format('d/m/Y')}} às 17h00">
                                                    <button type="submit" class="btn btn-success">Agendar</button>
                                                </a>
                                            @endif
                                        </td>
                                    </form>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
  
</div>

@push ('scripts')
  <script src="{{asset('assets/js/agendamento_massagem.js')}}"></script>
@endpush

@endsection


