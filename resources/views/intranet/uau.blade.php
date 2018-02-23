@extends('intranet.templates.template')

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">

                <h2>
                    <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                    Uau!
                    <a href="{{url('/intranet/meus-uaus')}}">
                    <button type="button" class="uaubtn btn btn-md btn-danger">
                        <i class="glyphicon glyphicon-heart"></i> Meus Uaus 
                        @if($unread_uaus>0)<span class="badge"> {{$unread_uaus}}</span>@endif
                    </button></a>
                    <a href="{{url('/intranet/novo-uau')}}">
                    <button type="button" class="btn btn-md btn-primary">
                        <i class="glyphicon glyphicon-send"></i> Enviar Uau
                    </button></a>
                </h2>

            </div>

            <div class="panel-body">
                
                <div class="row">

                    <div class="col-md-8">
                        <h3 class="text-center"><b>Todos os Uaus</b></h3>
                        <table class="table table-striped table-bordered table-hover">
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
                        <h3 class="text-center"><b>Ranking - Top 10</b></h3>
                        <div class="well">
                            @if(!$ranking->isEmpty())
                                @php
                                    $max = $ranking[0]->uaus;
                                @endphp
                            @endif 
                            
                                @foreach($ranking as $user)
                
                                <span>
                                    <strong>
                                        @if($user->id == Auth::user()->id) 
                                            <i class="glyphicon glyphicon-heart" style="color:red;"></i>
                                        @endif 
                                        {{$user->name}}
                                    </strong>
                                        - {{$user->uaus}} @if($user->uaus>1) Uaus! @else Uau! @endif
                                </span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="{{$user->uaus}}"
                                    aria-valuemin="0" aria-valuemax="{{$max}}" style="width:{{$user->uaus/$max*100}}%">
                                        {{$user->uaus}} @if($user->uaus>1) Uaus! @else Uau! @endif 
                                    </div>
                                </div>
                            
                            @endforeach
                        </div>
                    </div>
                
                </div>

            </div>

        </div>

@endsection