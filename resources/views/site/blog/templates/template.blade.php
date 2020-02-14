<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>{{$title or 'Izique Chebabi Advogados Associados'}}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="index, follow">
        <meta name="description" content="Escritório de advocacia com várias filias pelo estado de São Paulo e correspondentes por todo Brasil. Advogados Campinas, Advocacia trabalhista, Advocacia cível, contencioso, contratos, shopping, locação, empresarial.">
        <meta name="keywords" content="advogados,advocacia,escritório,trabalhista,cível,contratos,shopping,campinas,bebedouro,são,paulo">
        <link rel="icon" type="image/png" href="{{url('assets/imagens/icon.png')}}" />
        <!-- CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link href="{{url('assets/css/styles.css')}}" rel="stylesheet" type="text/css" media="all"/>
        <link rel="stylesheet" href="{{asset('assets/font-awesome-4.7.0/css/font-awesome.min.css')}}">
        @stack('styles')
        <!-- Fim CSS -->
        <!-- Web-font -->
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <!-- Fim Web-font -->
        <!-- Analytics -->
        <script src="{{url('assets/js/analytics.js')}}"></script>
        <!-- Fim Analytics -->
    </head>
    
    <body>

        <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light text-center">
            <div class="container">
                <a class="navbar-brand" href="http://www.chebabi.com/">
                    <img class="img-fluid" src="{{url('assets/imagens/logo_pq.png')}}" alt="advogados">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <span class="nav-link active span-blog"><b><i>/ BLOG /</i></b></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/blog')}}">ARTIGOS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/blog/noticias')}}">NOTÍCIAS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/blog/historias')}}">HISTÓRIAS</a>
                        </li>
                        <li class="nav-item">
                                <span class="nav-link active span-blog"><b><i> / </i></b></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('/')}}">
                                <i class="fa fa fa-arrow-left"></i>
                                VOLTAR PARA O SITE
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">

            @yield('conteudo')

        </div>

        <footer class="footer bg-light text-center">
            <div class="container">

                <a href="https://www.linkedin.com/company/izique-chebabi-advogados-associados" target="_blank" class="fa fa-linkedin @if($agent->isMobile()) fa-2x @else fa-3x @endif social"></a>
                <a href="https://www.facebook.com/Izique-Chebabi-Advogados-Associados-346767155816975" target="_blank" class="fa fa-facebook @if($agent->isMobile()) fa-2x @else fa-3x @endif social"></a>
                <!-- <a href="#" class="fa fa-youtube @if($agent->isMobile()) fa-2x @else fa-3x @endif social"></a> -->
                <hr/>
                <div class="row row-eq-height">

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 text-justify display-grid">
                        <h4 class="text-center">
                            <a class="advs-link" target="_blank" href="https://www.google.com.br/maps/place/Izique+Chebabi+Advogados+Associados/@-22.9045245,-47.0595491,17z/data=!3m1!4b1!4m5!3m4!1s0x94c8cf4b41463e59:0x34f30ab1abcdae88!8m2!3d-22.9045295!4d-47.0573604">
                                CAMPINAS - SP
                            </a>
                        </h4>
                        <small class="text-left">
                            Rua Concei&ccedil;&atilde;o, 233, Cj. 102, 103, 
                            109 ao 115, Centro - Campinas - SP - CEP 13010-050 -
                            Fone/Fax: (19) 3203-4744 / (19) 3237-3747
                        </small>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 text-justify display-grid">
                        <h4 class="text-center">
                            <a class="advs-link" target="_blank" href="https://www.google.com/maps/place/Av.+Marqu%C3%AAs+de+S%C3%A3o+Vicente,+446+-+Barra+Funda,+S%C3%A3o+Paulo+-+SP,+01139-020/@-23.521579,-46.664113,17z/data=!4m5!3m4!1s0x94ce5808878cdd29:0x18f44bb73842b40f!8m2!3d-23.5214401!4d-46.6603877?hl=pt-BR">
                                SÃO PAULO - SP
                            </a>
                        </h4>
                        <small class="text-left">
                            Av. Marquês de São Vicente, 446, Salas 1501/1502, 
                            Barra Funda - São Paulo - SP - CEP 01139-000 -
                            Fone/Fax: (11) 2548-3960
                        </small>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 text-justify display-grid">
                        <h4 class="text-center">
                            <a class="advs-link" target="_blank" href="https://goo.gl/maps/84js4RqRHPQ2">
                                RIO DE JANEIRO - RJ
                            </a>
                        </h4>
                        <small class="text-left">
                            R. Evaristo da Veiga, 65, Torre 2, Sala 1501, 
                            Centro - Rio de Janeiro - RJ - CEP 20031-040 - 
                            Fone/Fax: (21) 2184-6206
                        </small>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 text-justify display-grid">
                        <h4 class="text-center">
                            <a class="advs-link" target="_blank" href="https://goo.gl/maps/RMG3YZsDxkt">
                                FLORIANÓPOLIS - SC
                            </a>
                        </h4>
                        <small class="text-left">
                            Av. Osvaldo Rodrigues Cabral, 1570, Centro
                            - Florian&oacute;polis - SC - CEP 88015-710
                            Fone/Fax: (48) 3024-0011                            
                        </small>
                    </div>

                </div>

                <hr/>
            </div>
            
            <p>© Copyright © {{Carbon\Carbon::now()->year}} - Izique Chebabi Advogados Associados. Todos os direitos reservados. Desenvolvido por <a href="mailto:victor.meireles.dev@gmail.com" target="_blank">Victor Meireles.</a></p>
        </footer>

    </body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    @stack ('scripts')
    
</html>