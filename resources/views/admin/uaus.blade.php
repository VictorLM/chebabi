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
            </div>
        </div>

        <div class="panel-body uau-index">
            
            <div class="row justify-content-md-center">
                
                <div class="col-md-6 col-md-offset-3">
                <h3 class="text-center"><b>Ranking - {{$semestre}}º Semestre {{Carbon\Carbon::now()->year}}</b></h3>
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
                                $count = 1;
                            @endphp
                            @foreach($ranking_sorted as $uaus => $users)
                                @foreach($users as $user)
                                    {{$count}}º
                                    @if($uaus == $key1) 🏆 @elseif($uaus == $key2) 🥈 @elseif($uaus == $key3) 🥉 @endif

                                    <span class="nome-uau-ranking">
                                        <strong>{{$user['name']}}</strong> - 
                                        <span>{{$uaus}} @if($uaus>1) Uaus! @else Uau! @endif</span>
                                    </span>
                                    <hr class="hr-advs-clientes"/>
                                    
                                        
                                    @php $count++; @endphp
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