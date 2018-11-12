@extends('layouts.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Blog - Novo Artigo</h2>
            <small>Os Artigos são exibidos no <a target="_blank" href="{{url('/blog')}}">Blog</a>. A imagem não é obrigatória.</small>
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

            <form id="form" method="POST" enctype="multipart/form-data" action="{{action('Blog\BlogController@inserir_artigo')}}">
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

                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6{{ $errors->has('autor') ? ' has-error' : '' }}">
                        <label>* Autor</label>
                        <input type="text" class="form-control" name="autor" maxlength="100" value="{{old('autor')}}" required>
                        @if ($errors->has('autor'))
                            <span class="help-block">
                                <strong>{{ $errors->first('autor') }}</strong>
                            </span>
                        @endif
                    </div>

                </div>

                <div class="row">

                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6{{ $errors->has('categoria') ? ' has-error' : '' }}">
                        <label>* Categoria</label>
                        <select class="form-control" name="categoria" required>
                            <option value=""></option>
                            <option value="Cível" @if(old('categoria')=='Cível') selected @endif>Cível</option>
                            <option value="Trabalhista" @if(old('categoria')=='Trabalhista') selected @endif>Trabalhista</option>
                            <option value="Outros" @if(old('categoria')=='Outros') selected @endif>Outros</option>
                        </select>
                        @if ($errors->has('categoria'))
                            <span class="help-block">
                                <strong>{{ $errors->first('categoria') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6{{ $errors->has('imagem') ? ' has-error' : '' }}">
                        <label>Imagem (Tamanho máximo 1MB)</label>
                        <input type="file" class="form-control" name="imagem">
                        @if ($errors->has('imagem'))
                            <span class="help-block">
                                <strong>{{ $errors->first('imagem') }}</strong>
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
                        <label>* Descrição (Máximo 20.000 caracteres)</label>
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
                    </div>

                </div>

            </form>
        </div>
    </div>

    <!-- Modal -->
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

