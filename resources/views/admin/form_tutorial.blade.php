@extends('layouts.app')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Tutorial</h2>
                    <small>Os tutoriais podem ser acessados por todas os usuários.</small>
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
                    
                    <form class="form-horizontal" id="form" method="POST" enctype="multipart/form-data" action="{{action('Admin\AdminController@novo_tutorial')}}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">* Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" maxlength="100" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('arquivo') ? ' has-error' : '' }}">
                            <label for="arquivo" class="col-md-4 control-label">* Arquivo</label>

                            <div class="col-md-6">
                                <input id="arquivo" type="file" class="form-control" name="arquivo" required>
                                <small>** Somente PDF.</small>
                                @if ($errors->has('arquivo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('arquivo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                        <span>* Campos obrigatórios.</span><br/>
                        <span>** Tamanho máximo 5MB.</span>
                    </form>
                </div>
            </div>
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
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>
@endpush

@endsection
