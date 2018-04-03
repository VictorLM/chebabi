@extends ('site.templates.template')

@section('conteudo')

    <div class="card bg-light mb-3">
        <div class="card-body">

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
            
            <hr/>
        
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
                                    </p>
                                @endforeach
                            @else
                                <p class="card-text">Nenhum artigo encontrado.</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog')}}" class="btn btn-primary btn-sm">Ver tudo</a>
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
                                        <p class="card-text card-text-home"><a href="{{$noticia->link}}" target="_blank">{{$noticia->titulo}}</a></p>
                                    @endforeach
                                @else
                                    <p class="card-text card-text-home">Nenhuma notícia encontrada.</p>
                                @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog/noticias')}}" class="btn btn-primary btn-sm">Ver todas</a>
                        </div>

                    </div>
                    
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
        
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
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item rounded" src="{{$historia->link}}" allowfullscreen></iframe>
                                    </div>
                                    <br/>
                                    <p class="card-text card-text-home"></p>
                                @endforeach
                            @else
                                <p class="card-text">Nenhuma história encontrado.</p>
                            @endif
                        </div>

                        <div class="card-footer" id="escritorio">
                            <a href="{{url('/blog/historias')}}" class="btn btn-primary btn-sm">Ver tudo</a>
                        </div>

                    </div>
                    
                </div>
            
            </div>
            
            <hr/>
            <h1 class="text-center">O ESCRITÓRIO</h1>
            <hr/>
        
            <div class="row">
        
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-justify">
                    <p>
                        &emsp;Fundado em 1998, o escritório <strong>IZIQUE CHEBABI ADVOGADOS ASSOCIADOS</strong> re&uacute;ne a  
                        experi&ecirc;ncia de mais de 30 anos de sua s&oacute;cia <strong>MARILDA IZIQUE CHEBABI</strong>,  
                        Desembargadora do Tribunal Regional do Trabalho da 15a Regi&atilde;o,  aposentada, ex-professora da 
                        Escola Superior da Magistratura, de cursos de  p&oacute;s-gradua&ccedil;&atilde;o e autora de in&uacute;meros 
                        trabalhos publicados na &aacute;rea  trabalhista.</p><br/>
        
                        <p>Ao lado da reconhecida trajet&oacute;ria da s&oacute;cia fundadora, o  escrit&oacute;rio conta com advogados e 
                        consultores com s&oacute;lida e cont&iacute;nua forma&ccedil;&atilde;o  acad&ecirc;mica, especialistas na &aacute;rea 
                        trabalhista, sindical, empresarial, c&iacute;vel,  banc&aacute;ria, comercial, contratual, tribut&aacute;ria, administrativa, 
                        ambiental,  previdenci&aacute;ria e consumidor, com atua&ccedil;&atilde;o consultiva (pareceres e orienta&ccedil;&otilde;es) e  
                        contenciosa (judicial e administrativa).</p><br/>
        
                        <p>A busca por solu&ccedil;&otilde;es r&aacute;pidas e a constante preocupa&ccedil;&atilde;o com  resultados, 
                        como a redu&ccedil;&atilde;o de passivos trabalhistas, c&iacute;veis e fiscais e a  elimina&ccedil;&atilde;o 
                        de riscos jur&iacute;dicos e, ainda, os investimentos em ferramentas de  atualiza&ccedil;&atilde;o de processos pela internet, 
                        com emiss&atilde;o peri&oacute;dica de relat&oacute;rios por  e-mail, resumem os tr&ecirc;s principais pontos de destaque do 
                        escrit&oacute;rio: efici&ecirc;ncia,  compet&ecirc;ncia e transpar&ecirc;ncia.</p><br/>
        
                        <p>Essa postura, aliada &agrave;s constantes orienta&ccedil;&otilde;es voltadas &agrave; tomada  de decis&otilde;es estrat&eacute;gicas, 
                        com suporte jur&iacute;dico a novos neg&oacute;cios, t&ecirc;m rendido ao  escrit&oacute;rio clientes destacados no cen&aacute;rio 
                        nacional e internacional.</p><br/>
        
                        <p>Pela privilegiada localiza&ccedil;&atilde;o de sua sede, na cidade de Campinas  e de suas filiais em S&atilde;o Paulo, 
                        Florian&oacute;polis e Bebedouro, a menos de 100 km de S&atilde;o Jos&eacute; do Rio Preto, com  correspondentes em  
                        Santos, Bauru, Ribeir&atilde;o Preto e Taubat&eacute;, o  escrit&oacute;rio atende a seus clientes nas principais cidades 
                        e regi&otilde;es do Estado de  S&atilde;o Paulo.
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
                    <p><i> - Encantar o cliente com atendimento personalizado, oferecendo soluções eficientes para a defesa dos seus interesses.</i></p>
                    <br/>
                    <h2>VISÃO</h2>
                    <p><i> - Ser a principal referência na prestação de serviços jurídicos na área de direito empresarial.</i></p>
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

@endsection
