@extends('layouts.app')

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (empty($user))
                        <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Usuário</h2>
                    @else
                        <h2><a href="{{url('/intranet/admin/users')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Editar Usuário</h2>
                    @endif
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
                    
                    @if (!empty($user))
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{action('User\UserController@update', $user->id)}}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                    @else
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('register') }}">
                        {{ csrf_field() }}
                    @endif

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">* Nome</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" maxlength="100" value="{{ $user->name or old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">* E-mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" maxlength="50" value="{{ $user->email or old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                            <label for="tipo" class="col-md-4 control-label">* Tipo</label>

                            <div class="col-md-6">
                                <select class="form-control" name="tipo" id="tipo" required>
                                    <option></option>
                                    <option value="admin" @if((isset($user) && $user->tipo=='admin') || old('tipo') == "admin") selected @endif>Administrador</option>
                                    <option value="adv" @if((isset($user) && $user->tipo=='adv') || old('tipo') == "adv") selected @endif>Advogado</option>
                                    <option value="adm" @if((isset($user) && $user->tipo=='adm') || old('tipo') == "adm") selected @endif>Administrativo</option>
                                    <option value="fin" @if((isset($user) && $user->tipo=='fin') || old('tipo') == "fin") selected @endif>Financeiro</option>
                                    <option value="admjur" @if((isset($user) && $user->tipo=='admjur') || old('tipo') == "admjur") selected @endif>Adm. Jurídico</option>
                                </select>

                                @if ($errors->has('tipo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('ramal') ? ' has-error' : '' }}">
                            <label for="ramal" class="col-md-4 control-label">* Ramal</label>

                            <div class="col-md-6">
                                <input id="ramal" type="number" class="form-control" name="ramal" maxlength="3" value="{{ $user->ramal or old('ramal') }}" required>

                                @if ($errors->has('ramal'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ramal') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('telefone') ? ' has-error' : '' }}">
                            <label for="telefone" class="col-md-4 control-label">Telefone</label>

                            <div class="col-md-6">
                                <input id="telefone" type="text" class="form-control sp_celphones" name="telefone" maxlength="20" placeholder="(19) 99999-9999" value="{{ $user->telefone or old('telefone') }}">

                                @if ($errors->has('telefone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('telefone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('nascimento') ? ' has-error' : '' }}">
                            <label for="nascimento" class="col-md-4 control-label">Data de nascimento</label>

                            <div class="col-md-6">
                                <input id="nascimento" type="date" class="form-control" name="nascimento" maxlength="10" value="{{ $user->nascimento or old('nascimento') }}">

                                @if ($errors->has('nascimento'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nascimento') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">
                                @if(!isset($user)) * @endif Senha
                            </label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" @if(!isset($user)) required @endif>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">
                                @if(!isset($user)) * @endif Confirme a senha
                            </label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" @if(!isset($user)) required @endif>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @if (empty($user))
                                        Cadastrar
                                    @else
                                        Atualizar
                                    @endif
                                </button>
                            </div>
                        </div>
                        <span>* Campos obrigatórios.</span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push ('scripts')
    <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('assets/js/tel_mask.js') }}"></script>
@endpush

@endsection
