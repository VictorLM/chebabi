@extends ('site.templates.template')

@section('conteudo')
    
    @if(isset($alertas_site))
        @foreach($alertas_site as $alerta)
            <div class="alert alert-{{$alerta->tipo}} alert-dismissible fade show" role="alert" style="margin-top:1rem;">
                {{$alerta->descricao}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif

    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">

            <div class="carousel-item active">
                <img class="d-block img-fluid" src="{{url('assets/imagens/slides/banner-1.jpg')}}" alt="advogados">
            </div>
            <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/imagens/slides/banner-2.jpg')}}" alt="advogados">
            </div>
            <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/imagens/slides/banner-3.jpg')}}" alt="advogados">
            </div>

        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="card bg-light mb-3">
        <div class="card-body">

            <div class="row row-eq-height">
        
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
        
                    <div class="card">
                        <div class="card-header">
                            <a class="h4-link" href="{{url('/blog')}}">
                                <h4 class="text-center">
                                    <i class="fa fa-file-text"></i> ARTIGOS
                                </h4>
                            </a>
                        </div>
        
                        <div class="card-body">
                            @if(count($artigos)>0)
                                @foreach ($artigos as $artigo)
                                    <p class="card-text card-text-home">
                                        <a href="{{$artigo->url}}" target="_blank">{{$artigo->titulo}}</a>
                                        <span style="color: grey;"> - <i>{{Carbon\Carbon::parse($artigo->created_at)->format('d/m')}}</i></span>
                                    </p>
                                @endforeach
                            @else
                                <p class="card-text">Nenhum artigo encontrado.</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog')}}" class="btn btn-primary btn-sm">Veja mais</a>
                        </div>

                    </div>
                    
                </div>
            
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
        
                    <div class="card">
                        <div class="card-header">
                            <a class="h4-link" href="{{url('/blog/noticias')}}">
                                <h4 class="text-center">
                                    <i class="fa fa-newspaper-o"></i> NOTÍCIAS
                                </h4>
                            </a>
                        </div>
        
                        <div class="card-body">
                                @if(count($noticias)>0)
                                    @foreach($noticias as $noticia)
                                        <p class="card-text card-text-home">
                                            <a href="{{$noticia->link}}" target="_blank">{{$noticia->titulo}}</a>
                                            <span style="color: grey;"> - <i>{{Carbon\Carbon::parse($noticia->publicacao)->format('d/m')}}</i></span>
                                        </p>
                                    @endforeach
                                @else
                                    <p class="card-text card-text-home">Nenhuma notícia encontrada.</p>
                                @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog/noticias')}}" class="btn btn-primary btn-sm">Veja mais</a>
                        </div>

                    </div>
                    
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 text-justify display-grid">

                    <div class="card">
                        <div class="card-header">
                            <a class="h4-link" href="{{url('/blog/historias')}}">
                                <h4 class="text-center">
                                    <i class="fa fa-youtube-play"></i> HISTÓRIAS
                                </h4>
                            </a>
                        </div>
        
                        <div class="card-body">
                            @if(count($historias)>0)
                                @foreach ($historias as $historia)
                                    @if(!empty($historia->link))
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item rounded" src="{{$historia->link}}" allowfullscreen></iframe>
                                        </div>
                                        <p class="card-text card-text-home"></p>
                                    @else
                                        <p class="card-text card-text-home">
                                            <a href="{{$historia->url}}" target="_blank">{{$historia->titulo}}</a>
                                            <span style="color: grey;"> - <i>{{Carbon\Carbon::parse($historia->created_at)->format('d/m')}}</i></span>
                                        </p>
                                    @endif
                                @endforeach
                            @else
                                <p class="card-text">Nenhuma história encontrado.</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog/historias')}}" class="btn btn-primary btn-sm">Veja mais</a>
                        </div>

                    </div>

                </div>
            
            </div>

            <hr/>
            <h1 class="text-center"><a class="h4-link" href="{{url('/tour-virtual')}}" target="_blank">TOUR VIRTUAL</a></h1>
            <hr/>
        
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="embed-responsive embed-responsive-21by9">
                        <iframe id="tour-home" class="embed-responsive-item" src="https://my.matterport.com/show/?m=Fkw4fpvqNe4&play=0&help=1" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            
            <hr/>
            <h1 class="text-center" id="escritorio">O ESCRITÓRIO</h1>
            <hr/>
        
            <div class="row">
        
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-justify">
                    <p>
                        &emsp;Há 20 anos atuando na área jurídica empresarial, unindo tradição, modernidade e vanguarda, o escritório 
                        Izique Chebabi Advogados Associados se orgulha de oferecer aos seus clientes um serviço jurídico especializado, 
                        competente, eficiente, estratégico e, sobretudo, comprometido com os interesses do cliente.
                    </p><br/>
                    <p>
                        Com o foco em soluções jurídicas responsáveis e olhos no resultado, a equipe, integrada por talentos de alta 
                        performance, desenvolve a melhor estratégia para cada caso.
                    </p><br/>
                    <p>
                        A interação com o cliente para compreender as suas necessidades, anseios, preocupações e expectativas, 
                        sempre com o propósito de prestar o serviço como o cliente quer, deseja e merece ser atendido, aliado 
                        ao constante fortalecimento do espírito de equipe entre os seus destacados profissionais, são os principais 
                        ingredientes dessa receita de sucesso.
                    </p><br/>
                    <p>
                        E é por isso que as 60 pessoas que fazem parte do Izique Chebabi Advogados Associados se orgulham de trabalhar 
                        numa banca de advocacia que ainda prestigia a manutenção de um ambiente familiar. Afinal, são 20 anos construindo 
                        relacionamentos, fortalecendo os vínculos pessoais, estabelecendo parcerias e dividindo experiências, tudo como 
                        uma grande família. 
                    </p>
                </div>
        
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 home-img text-center">
                    <img class="img-fluid" alt="advogados" title="advogados" src="{{url('assets/imagens/body/b1.jpg')}}">
                    <hr/>
                    <img class="img-fluid" alt="advogados" title="advogados" src="{{url('assets/imagens/body/b2.jpg')}}">
                </div>
        
            </div>
            
            <hr id="principios"/>
            <h1 class="text-center">PRINCÍPIOS</h1>
            <hr/>
        
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-jutify">
                    <h2>MISSÃO</h2>
                    <ul>
                        <li>
                            <i>Encantar o cliente com atendimento personalizado, oferecendo soluções eficientes para a defesa dos seus interesses.</i>
                        </li>
                    </ul>
                    <br/>
                    <h2>VISÃO</h2>
                    <ul>
                        <li>
                            <i>Ser a principal referência na prestação de serviços jurídicos na área de direito empresarial.</i>
                        </li>
                    </ul>
                    <br/>
                    <h2>VALORES</h2>
                    <ul>
                        <li><i>	Admirar a profissão, o cliente e o colega de trabalho.</i></li>
                        <li><i>	Agradar ao cliente com pro atividade.</i></li>
                        <li><i>	Ouvir a opinião do cliente e a do colega de trabalho.</i></li>
                        <li><i>	Servir os colegas de trabalho.</i></li>
                    </ul>
                </div>
            </div>
        
            <hr/>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalHome" tabindex="-1" role="dialog" aria-labelledby="modalHome" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Coronavírus: Medidas Trabalhistas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body h5">
                    Acesse nosso material sobre as recomendações trabalhistas 
                    <a href="/medidastrabalhistascoronavirus">clicando aqui</a>.
                    <br/><br/>
                    <h5 class="text-center"><b>ERRATA</b></h4>
                    <p class="h6 text-justify">
                    MEDIDAS TRABALHISTAS PARA ENFRENTAMENTO DA EMERGÊNCIA DE SAÚDE PÚBLICA DECORRENTES DO CORONAVÍRUS, 23 de março de 2020.
                    No tocante ao FGTS, para poder usufruir da prerrogativa de parcelamento dos recolhimentos de março, abril e maio de 2020, 
                    o empregador precisa fazer a declaração das informações até o dia 20 de junho de 2020 e não de julho, como havíamos publicado na página 6.
                    </p>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalHome2" tabindex="-1" role="dialog" aria-labelledby="modalHome2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">MP DA REDUÇÃO SALARIAL E DA SUSPENSÃO DO CONTRATO</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body h5">
                    Acesse nosso material sobre as recomendações  
                    <a href="/mpdareducaosalarialedasuspensaodocontrato">clicando aqui</a>.
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push ('scripts')
    <script>
        $('#modalHome2').modal('show');
        $('#modalHome2').on('hidden.bs.modal', function () {
            $('#modalHome').modal('show')
        });
    </script>
@endpush