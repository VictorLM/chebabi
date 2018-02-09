@extends('layouts.app')

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Procedimento</h2>
                    <small>Os procedimentos podem ser acessados apenas pelos usuários da respectiva área, exceto os de tipo "Geral".</small>
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
                    
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{action('Admin\AdminController@novo_procedimento')}}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">* Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control text-uppercase" name="name" maxlength="100" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                            <label for="tipo" class="col-md-4 control-label">* Tipo</label>

                            <div class="col-md-6">
                                <select class="form-control" name="tipo" id="tipo" required>
                                    <option value="{{ old('tipo') }}">{{ old('tipo') }}</option>
                                    <option value="admin">Administrador</option>
                                    <option value="adv">Advogado</option>
                                    <option value="adm">Administrativo</option>
                                    <option value="admjur">Adm. Jurídico</option>
                                    <option value="fin">Financeiro</option>
                                    <option value="geral">Geral</option>
                                </select>

                                @if ($errors->has('tipo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
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
</div>
@endsection
