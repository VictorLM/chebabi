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
                    @if (empty($adv))
                        <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Advogado</h2>
                    @else
                        <h2><a href="{{url('/intranet/admin/advs')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Editar Advogado</h2>
                    @endif
                    <small>Os advogados são exibidos na seção "Equipe" do site.</small><br/>
                    <small>Para cadastrar um advogado é necessário primeiro te-lô cadastrado como usuário do tipo "Advogado".</small>
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
                    
                    @if (!empty($adv))
                        <form class="form-horizontal" method="POST" id="form" enctype="multipart/form-data" action="{{action('Advogados\AdvogadosController@update', $adv->id)}}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                    @else
                        <form class="form-horizontal" method="POST" id="form" enctype="multipart/form-data" action="{{action('Advogados\AdvogadosController@store')}}">
                        {{ csrf_field() }}
                    @endif
                    
                        <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }}">
                            <label for="para" class="col-md-4 control-label">* Usuário</label>
                            
                            <div class="col-md-6">
                                @if (empty($adv))
                                    <select class="form-control" name="usuario" required>
                                        <option></option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" @if(old('usuario')==$user->id) selected @endif>{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-control" name="usuario" required readonly>
                                        <option value="{{$adv->nome_usuario->id}}">{{$adv->nome_usuario->name}}</option>
                                    </select>
                                @endif
                                @if ($errors->has('usuario'))
                                    <small style="color:red;">
                                        {{ $errors->first('usuario') }}
                                    </small>
                                    <br/>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('oab') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">* OAB</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="oab" maxlength="14" value="{{ $adv->oab or old('oab') }}" placeholder="123.456/SP" required>

                                @if ($errors->has('oab'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('oab') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('tipo_adv') ? ' has-error' : '' }}">
                            <label for="tipo_adv" class="col-md-4 control-label">* Tipo</label>

                            <div class="col-md-6">
                                <select class="form-control" name="tipo_adv" required>
                                    <option value=""></option>
                                    @if(isset($adv) && !empty($adv->tipo_adv))
                                        <option value="Cível" @if($adv->tipo_adv =="Cível") selected @endif>Cível</option>
                                        <option value="Trabalhista" @if($adv->tipo_adv =="Trabalhista") selected @endif>Trabalhista</option>
                                    @else
                                        <option value="Cível" @if(old('tipo_adv')=="Cível") selected @endif>Cível</option>
                                        <option value="Trabalhista" @if(old('tipo_adv')=="Trabalhista") selected @endif>Trabalhista</option>
                                    @endif
                                </select>

                                @if ($errors->has('tipo_adv'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo_adv') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('texto') ? ' has-error' : '' }}">
                            <label for="texto" class="col-md-4 control-label">* Texto</label>

                            <div class="col-md-6">
                                <textarea class="form-control" name="texto" maxlength="700" required>{{$adv->texto or old('texto')}}</textarea>

                                @if ($errors->has('texto'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('texto') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('fotoadv') ? ' has-error' : '' }}">
                            <label for="ramal" class="col-md-4 control-label">Foto</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control" name="fotoadv">
                                <small>*** SOMENTE FOTO QUADRADA E MÁXIMO 300KB.</small>
                                @if ($errors->has('fotoadv'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('fotoadv') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @if (empty($adv))
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
