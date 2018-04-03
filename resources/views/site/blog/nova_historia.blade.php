@extends('layouts.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Blog - Nova História</h2>
            <small>As Histórias são exibidas na seção <a target="_blank" href="{{url('/blog/historias')}}">Histórias</a> do <a target="_blank" href="{{url('/blog')}}">Blog.</a></small>
        </div>

        <div class="panel-body">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="form" method="POST" enctype="multipart/form-data" action="{{action('Blog\BlogController@inserir_historia')}}">
                {{ csrf_field() }}

                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6{{ $errors->has('titulo') ? ' has-error' : '' }}">
                        <label>* Título</label>
                        <input type="text" class="form-control" name="titulo" maxlength="200" value="{{old('titulo')}}" required>
                        @if ($errors->has('titulo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('titulo') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6{{ $errors->has('link') ? ' has-error' : '' }}">
                        <label>
                            ** Link 
                            <a data-toggle="modal" href="#link-tip"><i class="glyphicon glyphicon-question-sign"></i></a>
                        </label>
                        <input type="text" class="form-control" name="link" maxlength="100" value="{{old('link')}}" placeholder="https://www.youtube.com/embed/a1b2c3d4e5" required>
                        @if ($errors->has('link'))
                            <span class="help-block">
                                <strong>{{ $errors->first('link') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12{{ $errors->has('tags') ? ' has-error' : '' }}">
                        <label>* Tags</label>
                        <input type="text" class="form-control" name="tags" maxlength="200" value="{{old('tags')}}" placeholder="Separe as tags com vírgulas" required>
                        @if ($errors->has('tags'))
                            <span class="help-block">
                                <strong>{{ $errors->first('tags') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12{{ $errors->has('descricao') ? ' has-error' : '' }}">
                        <label>* Descrição</label>
                        <div id="editor"></div>
                        @if ($errors->has('descricao'))
                            <span class="help-block">
                                <strong>{{ $errors->first('descricao') }}</strong>
                            </span>
                        @endif
                        <input name="descricao" id="descricao" type="hidden" value="{{old('descricao')}}">
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12{{ $errors->has('descricao') ? ' has-error' : '' }}">
                        <button type="submit" class="btn btn-primary btn-lg">Cadastrar</button>
                        <br/><br/>
                        <span>* Campos obrigatórios.</span><br/>
                        <span>** O link do vídeo tem que ser do tipo "Embed Video". <a data-toggle="modal" href="#link-tip">Clique aqui</a> para ver o passo a passo.</span>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <!-- Modal 1 -->
    <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h2>Cadastrando...<br/>Aguarde.</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2 -->
    <div class="modal fade" id="link-tip" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="text-center">Para obter o link do tipo "Embed", siga os seguintes passos:</h3>
                    <p>1 - Acesse o vídeo desejado no <a target="_blank" href="https://www.youtube.com">YouTube</a>.</p>
                    <p>2 - Selecione a opção "Compartilhar" geralmente localizada sob o player do vídeo e depois clique na opção "Incorporar":</p>
                    <img class="img-fluid img-thumbnail rounded" src="{{url('assets/imagens/link_embed_tip_1.png')}}">
                    <br/>
                    <p>3 - No código que será exibido, copie somente o texto que estiver dentro das aspas duplas depois da tag "src":</p>
                    <img class="img-fluid img-thumbnail rounded" src="{{url('assets/imagens/link_embed_tip_2.png')}}">
                    <br/>
                    <p>Pronto! Esse é o link do tipo "Embed" que precisamos para vincular o vídeo com a história que será incluida.</p>
                    <p>Agora é só colar o texto copiado no campo "Link" do formulário de cadastro da história e preencher os demais campo.</p>
                </div>
            </div>
        </div>
    </div>

</div>

@push ('scripts')
    <script src="{{asset('assets/js/novo_artigo.js')}}"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor', {
        theme: 'snow'
    });
    </script>
@endpush

@endsection

