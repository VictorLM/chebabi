@extends('intranet.templates.template')
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

    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 cols-index">

        <div class="panel panel-default">
            <div class="panel-heading">Menu</div>

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
                            <a href="{{url('intranet/contatos')}}">
                                <i class="glyphicon glyphicon-phone-alt"></i>
                                <i class="glyphicon glyphicon-user"></i><br/>CONTATOS</a>
                        </div>
                    </div>

                </div>
                
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="#" id="popov" data-toggle="popover" data-trigger="focus" data-content="#">
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
                            <i class="glyphicon glyphicon-road"></i><br/>RELATÓRIO DE VIAGEM</a>
                        </div>
                    </div>

                </div>
                
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/tarifadores')}}">
                            <i class="glyphicon glyphicon-earphone"></i> 
                            <i class="glyphicon glyphicon-print"></i><br/>TARIFADORES</a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="intra-atalhos well well-lg">
                            <a href="{{url('intranet/tutoriais')}}">
                            <i class="glyphicon glyphicon-question-sign"></i>
                            <i class="glyphicon glyphicon-book"></i><br/>TUTORIAIS</a>
                        </div>
                    </div>

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

                </div>
                
                <div class="row">
                    
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

    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 cols-index">
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h2 class="uau-ranking-index">
                    <a class="uau-ranking-index" href="{{url('intranet/uau')}}">UAU! - Ranking</a>
                </h2>
            </div>
            <div class="panel-body">
                @if(!empty($ranking))
                    @php
                        $max = $ranking[0]->uaus;
                    @endphp
                    @foreach($ranking as $user)
                        <small>
                            <strong>
                                @if($user->id == Auth::user()->id) 
                                    <i class="glyphicon glyphicon-heart" style="color:red;"></i>
                                @endif 
                                {{$user->name}}
                            </strong>
                        </small>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$user->uaus}}"
                            aria-valuemin="0" aria-valuemax="{{$max}}" style="width:{{$user->uaus/$max*100}}%">
                                {{$user->uaus}} @if($user->uaus>1) Uaus! @else Uau! @endif 
                            </div>
                        </div>
                    @endforeach
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
                        <h1 class="modal-title" id="memberModalLabel">Parabéns!</h1>
                    </div>
                    <div class="modal-body">
                        <p>Parabéns pelo seu aniversário! Muita saúde e felicidade.</p>
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
                        <h2 class="modal-title" id="memberModalLabel">Parabéns {{Auth::user()->name}}, você recebeu um novo Uau!</h2>
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

</div>
@push ('scripts')
    <script src="{{ asset('assets/js/app.js') }}"></script>
@endpush
@endsection