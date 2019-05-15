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
                        @if(!$ranking->isEmpty())
                            @php $max = $ranking[0]->uaus; @endphp
                        @endif 
                        @foreach($ranking as $user)
            
                            <span class="nome-uau-ranking"><strong>
                                @if($loop->first) 🏆 @elseif($loop->index == 1) 🥈 @elseif($loop->index == 2) 🥉 @endif
                                @if($user->id == Auth::user()->id)<i class="glyphicon glyphicon-heart heart-icon"></i>@endif 
                                {{$user->name}}
                            </strong></span>
                            <div class="progress progress-uau">
                                <div class="progress-bar progress-bar-uau progress-bar-striped active" role="progressbar" aria-valuenow="{{$user->uaus}}"
                                aria-valuemin="0" aria-valuemax="{{$max}}" style="width:{{$user->uaus/$max*100}}%">
                                    <span style="font-size:1.5em;"><b>{{$user->uaus}} @if($user->uaus>1) Uaus! @else Uau! @endif</b></span>
                                </div>
                            </div>
                        
                        @endforeach
                    </div>
                </div>
            
            </div>

        </div>

    </div>

@endsection