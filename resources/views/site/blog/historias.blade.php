@extends ('site.blog.templates.template')

@section('conteudo')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('/blog')}}">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Histórias</li>
        </ol>
    </nav>

    <div class="card bg-light mb-3">
        <div class="card-body">

            <div class="row">
        
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-justify">

                    <h1 class="text-center"><i class="fa fa-youtube-play"></i> HISTÓRIAS</h1>

                    <div class="card">

                        <div class="card-body">
                            @if(count($historias)>0)
                                @foreach ($historias as $historia)

                                <div>
                                    <h3>
                                        <a class="advs-link" href="{{$historia->url}}">{{$historia->titulo}}</a>
                                    </h3>
                                    <small class="text-muted">
                                        <i>{{Carbon\Carbon::parse($historia->created_at)->format('d/m/Y H:i')}}</i>
                                    </small>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item rounded" src="{{$historia->link}}" allowfullscreen></iframe>
                                    </div>
                                    <br/>
                                    <p>&emsp;{{strip_tags(str_limit($historia->descricao, 330, ' [...]'))}} </p>
                                    <small class="text-muted"><i>Tags: </small><small>{{$historia->tags}}</i></small>
                                    <br/>
                                    <a href="{{$historia->url}}" class="btn btn-primary btn-sm">Ler mais</a>
                                    <br/>
                                </div>

                                <hr/>
                                    
                                @endforeach
                            @else
                                <p class="card-text">Nenhuma história encontrado.</p>
                            @endif
                        </div>
                    </div>
                    <br/>
                    {{ $historias->links() }}

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 float-right">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center"><i class="fa fa-search"></i> PROCURAR</h4>
                        </div>
        
                        <div class="card-body">
                            <gcse:search></gcse:search>
                            <!--
                            <div class="input-group">
                                <input type="text" class="form-control" name="procurar" placeholder="Procurar por...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                            -->
                        </div>

                    </div>
                    <hr/>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-center"><i class="fa fa-globe"></i> REDES SOCIAIS</h4>
                        </div>
        
                        <div class="card-body text-center">
                            <a href="https://www.linkedin.com/company/izique-chebabi-advogados-associados" target="_blank" class="fa fa-linkedin fa-2x social"></a>
                            <a href="https://www.facebook.com/Izique-Chebabi-Advogados-Associados-346767155816975" target="_blank" class="fa fa-facebook fa-2x social"></a>
                            <a href="#" class="fa fa-youtube fa-2x social"></a>
                            <a href="https://plus.google.com/+IziqueChebabiAdvogadosAssociadosCampinas" target="_blank" class="fa fa-google fa-2x social"></a>
                        </div>

                    </div>
                    <hr/>
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

                                    <div>
                                        <h4 class="card-title">
                                            <a class="advs-link" href="{{$artigo->url}}">{{$artigo->titulo}}</a>
                                        </h4>
                                        <small class="text-muted">
                                            <i>{{Carbon\Carbon::parse($artigo->created_at)->format('d/m/Y H:i')}} | Autor: {{$artigo->autor}}</i>
                                        </small>
        
                                        <div class="row">
        
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <p class="card-text-small text-justify">
                                                    &emsp;{{strip_tags(str_limit($artigo->descricao, 250, ' [...]'))}} <a href="{{$artigo->url}}">Ler mais</a>
                                                </p>
                                                <small class="text-muted"><i>Tags: Teste, teste2, teste3, teste4 </i></small>
                                            </div>
                                        </div>
                                        <hr/>
                                    </div>

                                @endforeach
                            @else
                                <p class="card-text">Nenhum artigo encontrado.</p>
                            @endif


                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog')}}" class="btn btn-primary btn-sm">Ver todos</a>
                        </div>

                    </div>

                    <hr/>
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
                                @foreach ($noticias as $noticia)
                                    <p class="card-text card-text-home">
                                        <a href="{{$noticia->link}}" target="_blank">{{$noticia->titulo}}</a>
                                    </p>
                                @endforeach
                            @else
                                <p class="card-text">Nenhuma notícia encontrada.</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            <a href="{{url('/blog/noticias')}}" class="btn btn-primary btn-sm">Ver todas</a>
                        </div>

                    </div>
                    
                </div>

            </div>


        </div>
    </div>

@endsection

@push ('scripts')
    <script>
        (function() {
            var cx = '001804698701894226975:1zlgk83bea8';
            var gcse = document.createElement('script');
            gcse.type = 'text/javascript';
            gcse.async = true;
            gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(gcse, s);
        })();
    </script>
@endpush
