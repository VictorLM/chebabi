@extends('layouts.app')

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Tarifador</h2>
                    <small>Os códigos tarifadores podem ser acessados por todas os usuários.</small>
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
                    
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{action('Admin\AdminController@novo_tarifador')}}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('cliente') ? ' has-error' : '' }}">
                            <label for="cliente" class="col-md-4 control-label">* Cliente</label>

                            <div class="col-md-6">
                                <input id="cliente" type="text" class="form-control text-uppercase" name="cliente" maxlength="100" value="{{ old('cliente') }}" required autofocus>

                                @if ($errors->has('cliente'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cliente') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
                            <label for="cliente" class="col-md-4 control-label">* Código telefone</label>

                            <div class="col-md-6">
                                <input id="tel" type="text" class="form-control" name="tel" maxlength="100" value="{{ old('tel') }}" required>

                                @if ($errors->has('tel'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tel') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('imp') ? ' has-error' : '' }}">
                            <label for="cliente" class="col-md-4 control-label">* Código impressão</label>

                            <div class="col-md-6">
                                <input id="imp" type="text" class="form-control" name="imp" maxlength="100" value="{{ old('imp') }}" required>

                                @if ($errors->has('imp'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('imp') }}</strong>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
