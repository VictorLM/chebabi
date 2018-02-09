<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if(!empty($title)) {{$title}} @else Intranet @endif</title>

    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/intranet') }}">
                    <img class="img-fluid" src="{{url('assets/imagens/logo3.png')}}"/>
                </a>
                <div class="navbar-right userelogout">
                    <!-- Authentication Links -->
                    @guest
                        <a href="{{ route('login') }}">Login</a>
                    @else
                        <i class="glyphicon glyphicon-user"></i><span> Olá, {{ Auth::user()->name }} - </span>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Sair</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <br/>
                        <br/>
                        <i class="glyphicon glyphicon-bullhorn"></i>
                        <span>Tem alguma idéia? </span>
                        <a href="{{ url('/intranet/sugestao') }}">Envie uma sugestão!</a>
                    @endguest
                </div>

            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @stack ('scripts')
</body>
</html>
