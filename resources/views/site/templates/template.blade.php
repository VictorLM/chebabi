<!DOCTYPE html>
<html>
    <head>
        <title>{{$title or 'Izique Chebabi Advogados Associados'}}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="robots" content="index, follow">
        <meta name="description" content="Escritório de advocacia com várias filias pelo estado de São Paulo e correspondentes por todo Brasil. Advogados Campinas, Advocacia trabalhista, Advocacia cível, contencioso, contratos, shopping, locação, empresarial.">
        <meta name="keywords" content="advogados,advocacia,escritório,trabalhista,cível,contratos,shopping,campinas,bebedouro,são,paulo">
        <link rel="icon" type="image/png" href="{{url('assets/imagens/icon.png')}}" />
        <!-- CSS -->
        <link href="{{url('assets/css/style.css')}}" rel="stylesheet" type="text/css" media="all"/>
        <link href="{{url('assets/css/mobile.css')}}" rel="stylesheet" type="text/css" media="all"/>
        @stack ('styles')
        <!-- Fim CSS -->
        <!-- Web-font -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <!-- Fim Web-font -->
        <!-- Analytics -->
        <script src="{{url('assets/js/analytics.js')}}"></script>
        <!-- Fim Analytics -->
    </head>
    
    <body>
        
        <div id="main">
        <!-- Logo -->
        <div id="logo">
            <a href="http://www.chebabi.com/"><img alt="advogados" title="advogados" src="{{url('assets/imagens/logo.png')}}"/></a>
        </div>
        <!-- Fim Logo -->
        <!-- Menu -->
        <div id="menu">
            <div class="mobile-navigation">
                <input id="toggleMenu" type="checkbox" name="mobile-navigation">
                <label for="toggleMenu"><img alt="advogados" title="advogados" src="{{url('assets/imagens/menu.png')}}"/></label>
                <ul class="mobile-menu">
                <li><a href="{{url('/#conteudo')}}">SOBRE</a></li>
                <li><a href="{{url('/areas#conteudo')}}">ÁREAS</a></li>
                <li><a href="{{url('/advogados#conteudo')}}">EQUIPE</a></li>
                <li><a href="{{url('/escritorios#conteudo')}}">ESCRITÓRIOS</a></li>
                <li class="dropdown">
                    <a href="{{url('/contato#conteudo')}}">CONTATO</a>
                    <div class="dropdown-content">
                        <a href="{{url('/contato#conteudo')}}">CONSULTORIA JURÍDICA</a>
                        <a href="trabalhe-conosco#conteudo">TRABALHE CONOSCO</a>
                    </div>
                </li>
                <li><a href="{{url('/noticias#conteudo')}}">NOTÍCIAS</a></li>
                <li><a href="{{url('/intranet')}}">INTRANET</a></li>
            </ul>
            </div>
        </div>    
        <!-- Fim Menu -->

            @yield('conteudo')

        </div>

        <!-- Rodapé-->
        <footer>
            <a href="https://www.linkedin.com/company/izique-chebabi-advogados-associados" target="_blank"><img title="advocacia" alt="advocacia" src="{{url('assets/imagens/in.png')}}">&nbsp; LinkedIn</a>
            <a href="https://plus.google.com/+IziqueChebabiAdvogadosAssociadosCampinas" target="_blank"><img title="advocacia" alt="advocacia" src="{{url('assets/imagens/gplus.png')}}">&nbsp; Google Plus</a>
            <a href="{{url('/intranet')}}"><img title="advocacia" alt="advocacia" src="{{url('assets/imagens/colab.png')}}">&nbsp; Área do Colaborador</a>
            <p>© Copyright © {{Carbon\Carbon::now()->year}} - Izique Chebabi Advogados Associados. Todos os direitos reservados. Desenvolvido por <a href="http://festas.fun/" target="_blank">Victor Meireles.</a></p>
        </footer>
        <!-- Fim Rodapé -->
        @stack ('scripts')
    </body>
    
</html>