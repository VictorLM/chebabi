@extends('intranet.templates.template')

@section('content')

<div class="container-fluid">

    <div class="panel panel-default">

        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h2 style="margin-top:0px;">
                        <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                        Uau!
                    </h2>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <a href="{{url('/intranet/meus-uaus')}}">
                    <button type="button" class="uaubtn btn btn-sm btn-danger">
                        <i class="glyphicon glyphicon-heart"></i> Uaus 
                        @if($unread_uaus>0)<span class="badge"> {{$unread_uaus}}</span>@endif <br/> Recebidos
                    </button></a>
                    <a href="{{url('/intranet/uaus-enviados')}}">
                    <button type="button" class="btn btn-sm btn-success">
                        <i class="glyphicon glyphicon-star"></i> Uaus <br/> Enviados
                    </button></a>
                    <a href="{{url('/intranet/novo-uau')}}">
                    <button type="button" class="btn btn-sm btn-primary">
                        <i class="glyphicon glyphicon-send"></i> Enviar <br/> Uau!
                    </button></a>
                </div>
            </div>
        </div>

        <div class="panel-body uau-index">
            
            <div class="row">

                <div class="col-md-8">
                    <h3 class="text-center"><b>Todos os Uaus</b> ({{$count_uaus}})</h3>
                    <table class="table table-striped table-bordered table-hover uau-table">
                        <thead>
                            <tr>
                            <th>De</th>
                            <th>Para</th>
                            <th>Motivo</th>
                            <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($uaus as $uau)
                            @if ($uau->para_nome->ativo)
                                <tr>
                                    <td>{{$uau->de_nome->name}}</td>
                                    <td>{{$uau->para_nome->name}}</td>
                                    <td>{{$uau->motivo}}</td>
                                    <td>{{Carbon\Carbon::parse($uau->created_at)->format('d/m/y')}}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
            
                    {!! $uaus->links() !!}
                </div>
                
                <div class="col-md-4">
                    <h3 class="text-center">
                        @if(Carbon\Carbon::now()->month > 0 && Carbon\Carbon::now()->month < 7)
                            <b>Ranking - 1º Semestre {{Carbon\Carbon::now()->year}}</b>
                        @else
                            <b>Ranking - 2º Semestre {{Carbon\Carbon::now()->year}}</b>
                        @endif
                    </h3>
                    <div class="well uau-well">
                        @if(!empty($ranking_sorted))
                            @php
                                $key1 = 0;
                                $key2 = 0;
                                $key3 = 0;
                                $keys = array_keys($ranking_sorted);
                                if(!empty($ranking_sorted)){
                                    reset($ranking_sorted);
                                    $max = key($ranking_sorted);
                                    $key1 = $max;
                                    if(count($ranking_sorted) >= 3){
                                        $key2 = $keys[1];
                                        $key3 = $keys[2];
                                    }else if(count($ranking_sorted) == 2){
                                        $key2 = $keys[1];
                                    }
                                }
                            @endphp
                            @foreach($ranking_sorted as $uaus => $users)
                                @foreach($users as $user)
                                {{--@php dd($key3); @endphp--}}

                                    @if($uaus == $key1) 🏆 @elseif($uaus == $key2) 🥈 @elseif($uaus == $key3) 🥉 @endif

                                    @if($user['name'] == Auth::user()->name) <i class="glyphicon glyphicon-heart" style="color:red;"></i> @endif
                                    <span class="nome-uau-ranking">
                                        <strong>{{$user['name']}}</strong>
                                    </span>
                                    <div class="progress progress-uau">
                                        <div class="progress-bar progress-bar-uau progress-bar-striped active" role="progressbar" aria-valuenow="{{$uaus}}"
                                        aria-valuemin="0" aria-valuemax="{{$max}}" style="width:{{$uaus/$max*100}}%">
                                            <span style="font-size:1.5em;"><b>{{$uaus}} @if($uaus>1) Uaus! @else Uau! @endif</b></span>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            <h4 class="text-center">Ainda não há UAUs nesse semestre. =(</h4>
                            <p class="text-center"><a href="{{url('/intranet/novo-uau')}}">Envie um agora mesmo!</a></p>
                        @endif 
                    </div>
                </div>
            
            </div>

        </div>

    </div>

@endsection