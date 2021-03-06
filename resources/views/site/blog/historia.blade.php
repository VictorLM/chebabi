@extends ('site.blog.templates.template')

@section('conteudo')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('/blog')}}">Blog</a></li>
            <li class="breadcrumb-item"><a href="{{url('/blog/historias')}}">Histórias</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$historia->titulo}}</li>
        </ol>
    </nav>

    @if(Session::has('alert-success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('alert-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(Session::has('alert-error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('alert-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(Session::has('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card bg-light mb-3">
        <div class="card-body">

            <div class="row">
        
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-justify">

                    @if(!empty($historia))

                        <div class="card">

                            <div class="card-body">
                                <h2 class="card-title">{{$historia->titulo}}</h2>
                                <small class="text-muted">
                                    <i>{{Carbon\Carbon::parse($historia->created_at)->format('d/m/Y H:i')}}</i>
                                </small>
                                <hr/>
                                @if(!empty($historia->link))
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item rounded" src="{{$historia->link}}" allowfullscreen></iframe>
                                    </div>
                                @endif
                                <hr/>
                                <p>&emsp;{!!$historia->descricao!!}</p>
                                <small class="text-muted"><i>Tags: </small><small>{{$historia->tags}}</i></small>
                                <hr/>
                                <div class="text-right">
                                    <h2 class="inline">Compartilhe:</h2>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=http%3A//www.chebabi.com{{$historia->url}}&title={{$historia->titulo}}&summary=&source=" target="_blank" class="fa fa-linkedin fa-2x social"></a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=http%3A//www.chebabi.com{{$historia->url}}/" target="_blank" class="fa fa-facebook fa-2x social"></a>
                                </div>

                                <hr/>

                                <div class="card">
                                    <h5 class="card-header">Deixe seu comentário <small style="font-size:0.7em;"> - Seu e-mail não será divulgado.</small></h5>
                                    <div class="card-body">
                                        <form class="form-horizontal" id="form" method="POST" action="{{action('Blog\BlogController@comentar_historia', $historia->id)}}">
                                            {{ method_field('PUT') }}
                                            {{ csrf_field() }}
                                            <div class="form-row">
                                                <div class="col">
                                                    <input type="text" name="nome" value="{{old('nome')}}" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" placeholder="* Nome" maxlength="50" required>
                                                    @if ($errors->has('nome'))
                                                        <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="email" value="{{old('email')}}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="* Email" maxlength="100" required>
                                                    @if ($errors->has('email'))
                                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="form-group">
                                                <textarea class="form-control{{ $errors->has('mensagem') ? ' is-invalid' : '' }}" name="mensagem" placeholder="* Mensagem (máximo 500 caracteres)" maxlength="500">{{old('mensagem')}}</textarea>
                                                @if ($errors->has('mensagem'))
                                                    <div class="invalid-feedback">{{ $errors->first('mensagem') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-8">
                                                    {!! NoCaptcha::display() !!}
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <br/>
                                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <hr/>

                                <p>Comentários:</p>

                                @if(isset($comentarios) && count($comentarios)>0)
                                    @foreach ($comentarios as $comentario)

                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mt-0" style="display:inline;"><b>{{$comentario->autor}}</b></h5>
                                                <small class="text-muted">&nbsp;{{Carbon\Carbon::parse($comentario->created_at)->format('d/m/Y H:i')}}</small>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">&emsp; {{$comentario->comentario}}</p>
                                            </div>
                                        </div>
        
                                        <hr/>
                                    @endforeach

                                    <br/>
                                    {{ $comentarios->links() }}
                                @else
                                    <p>Ninguém comentou nesta história ainda. Seja o primeiro!</p>
                                @endif


                            </div>
                        </div>
    
                        <hr/>

                    @else
                        <p class="card-text">História não encontrada.</p>
                    @endif

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
                            <!-- <a href="#" class="fa fa-youtube fa-2x social"></a> -->
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
                                            <i>{{Carbon\Carbon::parse($artigo->created_at)->format('d/m/Y H:i')}} | Autor(a): {{$artigo->autor}}</i>
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

    <!-- Modal -->
    <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h2>Enviando...<br/>Aguarde.</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>

@endsection

@push ('scripts')
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>
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
