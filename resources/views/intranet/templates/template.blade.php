<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('meta')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(!empty($title)) {{$title}} @else Intranet @endif</title>
    <link rel="icon" type="image/png" href="{{url('assets/imagens/icon.png')}}" />
    
    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div id="app">

        <nav class="navbar navbar-default">
            <div class="container-index">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" style="margin-top:1.5em;">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/intranet') }}">
                        <img class="img-fluid" src="{{url('assets/imagens/logo_pq.png')}}"/>
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse userelogout">
                    <ul class="nav navbar-nav text-center" style="margin-top:1.2em;">
                        <li>
                            <img class="img-fluid" src="{{url('assets/imagens/wi-fi.png')}}"/> <b>Rede</b>: IC_Adv<br/>
                            <span><b>Senha</b>: fo5p8m33</span>{{-- //TODO Adicionar CRUD e puxar do DB pelo Controller --}}
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right text-left userelogout">
                        <li>
                            @guest
                                <a href="{{ route('login') }}">Login</a>
                            @else
                                <i class="glyphicon glyphicon-user"></i><span> Olá, <b>{{ Auth::user()->name }}</b>.</span>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="glyphicon glyphicon-log-out"></i> Sair
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <br/>
                                <i class="glyphicon glyphicon-bullhorn"></i>
                                <span>Tem alguma idéia?</span><a href="{{ url('/intranet/sugestao') }}">Envie uma sugestão!</a>
                            @endguest
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div><!--/.container-index -->
        </nav>
        
        {{-- 
        <nav class="navbar navbar-default">
            <div class="container-index"">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/intranet') }}">
                        <img class="img-fluid" src="{{url('assets/imagens/logo_pq.png')}}"/>
                    </a>
                </div>
                <ul class="nav navbar-nav navbar-right userelogout collapse navbar-collapse" id="navbar-collapse-1">
                    <li>
                        @guest
                            <a href="{{ route('login') }}">Login</a>
                        @else
                            <i class="glyphicon glyphicon-user"></i><span> Olá, <b>{{ Auth::user()->name }}</b>.</span>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="glyphicon glyphicon-log-out"></i> Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <br/>
                            <i class="glyphicon glyphicon-bullhorn"></i>
                            <span>Tem alguma idéia?</span><a href="{{ url('/intranet/sugestao') }}">Envie uma sugestão!</a>
                        @endguest
                    </li>
                </ul>
                </div>
            </div>
        </nav>
        --}}

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @stack ('scripts')
</body>
</html>
