@extends('intranet.templates.template')

@push('meta')
    <meta http-equiv="refresh" content="600"/>
@endpush

@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
@endpush

@section('content')

<div class="container-index">

    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <div class="alert alert-success alert-dismissable" style="margin-left:10px;margin-right:10px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session::get('alert-' . $msg) }}
            </div>
        @endif
    @endforeach

    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 cols-index">

        <div class="panel panel-default">

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                                <a href="{{url('intranet/agenda')}}">
                                <i class="glyphicon glyphicon-calendar"></i>
                                <i class="glyphicon glyphicon-time"></i><br/>AGENDA</a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            @if($aniversariantes>0)
                                <span class="badge float-right">{{$aniversariantes}}</span>
                            @endif
                            <a href="{{url('intranet/aniversariantes')}}">
                                <i class="glyphicon glyphicon-gift"></i>
                                <i class="glyphicon glyphicon-calendar"></i><br/>ANIVERSARIANTES</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="http://central.bragasistemas.com.br/brasisweb/cliente/" target="_blank">
                            <i class="glyphicon glyphicon-cog"></i>
                            <i class="glyphicon glyphicon-globe"></i><br/>CHAMADOS TI</a>
                        </div>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/clientes')}}">
                            <i class="glyphicon glyphicon-user"></i>
                            <i class="glyphicon glyphicon-briefcase"></i><br/>CLIENTES</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/contatos')}}">
                            <i class="glyphicon glyphicon-phone"></i>
                            <i class="glyphicon glyphicon-envelope"></i><br/>CONTATOS</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/correios')}}">
                            <i class="glyphicon glyphicon-envelope"></i>
                            <i class="glyphicon glyphicon-list-alt"></i><br/>CORREIOS</a>
                        </div>
                    </div>
   
                </div>
                
                <div class="row">

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="#" id="legalone" data-toggle="popover" data-trigger="focus" data-content="#">
                            <i class="glyphicon glyphicon-cloud"></i>
                            <i class="glyphicon glyphicon-globe"></i><br/>LEGAL ONE</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/procedimentos')}}"> 
                            <i class="glyphicon glyphicon-ok"></i>
                            <i class="glyphicon glyphicon-list-alt"></i><br/>PROCEDIMENTOS</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/relatorio')}}">
                            <i class="glyphicon glyphicon-file"></i>
                            <i class="glyphicon glyphicon-road"></i><br/>RELAT. DE VIAGENS</a>
                        </div>
                    </div>

                </div>
                
                <div class="row">

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/tarifadores')}}">
                            <i class="glyphicon glyphicon-phone-alt"></i> 
                            <i class="glyphicon glyphicon-print"></i><br/>TARIFADORES</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/terapias')}}">
                            <i class="glyphicon glyphicon-leaf"></i>
                            <i class="glyphicon glyphicon-time"></i><br/>TERAPIAS</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/tutoriais')}}">
                            <i class="glyphicon glyphicon-question-sign"></i>
                            <i class="glyphicon glyphicon-book"></i><br/>TUTORIAIS</a>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            @if($unread_uaus>0)
                                <span class="badge float-right">{{$unread_uaus}}</span>
                            @endif
                            <a href="{{url('intranet/uau')}}">
                            <i class="glyphicon glyphicon-heart"></i>
                            <i class="glyphicon glyphicon-star"></i><br/>UAU!</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="https://outlook.office365.com/" target="_blank">
                            <i class="glyphicon glyphicon-envelope"></i>
                            <i class="glyphicon glyphicon-globe"></i><br/>WEBMAIL</a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{route('logout')}}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="glyphicon glyphicon-arrow-left"></i>
                            <i class="glyphicon glyphicon-globe"></i><br/>VOLTAR PARA O SITE</a>
                        </div>
                    </div>
                    
                </div>

                <div class="row">

                    @if ($admin)
                        <div class="col-md-4">
                            <div class="intra-atalhos well well-lg">
                                <span class="glyphicon glyphicon-bookmark float-right" style="color:#337ab7;"></span>
                                <a href="{{url('intranet/admin')}}">
                                <i class="glyphicon glyphicon-cog"></i>
                                <i class="glyphicon glyphicon-lock"></i><br/>ADMINISTRADOR</a>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 cols-index">
        <div class="panel panel-default">
            <div class="panel-heading text-center header-padding-0">
                <h2 class="text-center header-padding-0">
                    <a class="uau-ranking-index" href="/intranet/uau">
                        @if(Carbon\Carbon::now()->month > 0 && Carbon\Carbon::now()->month < 7)
                            <b>UAU - 1º Semestre {{Carbon\Carbon::now()->year}}</b>
                        @else
                            <b>UAU - 2º Semestre {{Carbon\Carbon::now()->year}}</b>
                        @endif
                    </a>
                </h2>
            </div>
            <div class="panel-body">
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

    @if ($aniversario)
        <!-- Modal -->
        <div class="modal fade" id="parabensModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content aniversario">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body text-justify" style="font-weight:bold;">
                        <br/>
                        <h1 class="text-center">Parabéns, {{Auth::user()->name}}!</h1>
                        <p>Nesta data desejamos a você mais do que muitos anos de vida!</p>
                        <p>Desejamos que a cada ano você tenha novas conquistas para comemorar, que nunca lhe falte motivos para sorrir e que a sua vida seja carregada de felicidade, sonhos realizados e muito sucesso!</p>
                        <p>Que possamos comemorar não apenas o seu aniversário mas todas as suas realizações!</p>
                        <p class="text-center">Parabéns pelo seu dia!</p>
                    <div class="text-center">
                        <img class="img-fluid" src="{{url('assets/imagens/logo_pq.png')}}"/>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        @push ('scripts')
            <script type="text/javascript">
                $('#parabensModal').modal('show');
            </script>
        @endpush
    @endif
    
    @if ($unread_uaus>0)
        <!-- Modal -->
        <div class="modal fade" id="uauModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modaluau">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h1 class="modal-title text-center uau-title">UAU!</h1>
                    </div>
                    <div class="modal-body">
                        <h2 class="modal-title" id="memberModalLabel">Parabéns <b>{{Auth::user()->name}}</b>, você recebeu um novo Uau!</h2>
                        <h3>Clique <a href="{{url("/intranet/meus-uaus")}}">aqui</a> para visualizar.</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        @push ('scripts')
            <script type="text/javascript">
                $('#uauModal').modal('show');
            </script>
        @endpush
    @endif

    @if ($unread_parabens>0)
        <!-- Modal -->
        <div class="modal fade" id="unreadParabensModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-meus-parabens">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h1 class="modal-title text-center uau-title">Parabéns!</h1>
                    </div>
                    <div class="modal-body">
                        <h2 class="modal-title" id="memberModalLabel">Parabéns <b>{{Auth::user()->name}}</b>, você recebeu uma felicitação pelo seu aniversário!</h2>
                        <h3>Clique <a href="{{url("/intranet/aniversariantes/parabens")}}">aqui</a> para visualizar.</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        @push ('scripts')
            <script type="text/javascript">
                $('#unreadParabensModal').modal('show');
            </script>
        @endpush
    @endif

</div>
@push ('scripts')
    <script src="{{ asset('assets/js/app.js') }}"></script>
@endpush

@endsection