@extends('intranet.templates.template')
@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Enviar Correios</h2>

                    <small>A solicitação será enviada em seu nome, na data atual.</small>
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

                    <form class="form-horizontal" method="POST" id="form" enctype="multipart/form-data" action="{{action('Correios\CorreiosController@index')}}">
                    {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-6 @if($errors->has('reembolsavel')) has-error @endif" id="reembolsavel-div">
                                <label>* Reembolsável</label>
                                <select class="form-control" name="reembolsavel" required>
                                    <option value="">Selecione uma opção</option>
                                    <option value="1" @if(old('reembolsavel')=="1") selected @endif>Sim</option>
                                    <option value="0" @if(old('reembolsavel')=="0") selected @endif>Não</option>
                                </select>
                                @if ($errors->has('reembolsavel'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reembolsavel') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6 @if($errors->has('motivo')) has-error @endif" id="motivo-div" style="display:none;">
                                <label>* Não reembolsável? Motivo</label>
                                <input type="text" class="form-control" name="motivo" maxlength="191" 
                                placeholder="Digite o motivo aqui" value="{{ old('motivo') }}">
                                @if ($errors->has('motivo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('motivo') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6 @if($errors->has('tipo')) has-error @endif" id="tipo-div">
                                <label>* Tipo</label>
                                <select class="form-control" name="tipo" required>
                                    <option value="">Selecione um tipo</option>
                                    <option value="Carta_AR" @if(old('tipo')=="Carta_AR") selected @endif>Carta com AR</option>
                                    <option value="Sedex" @if(old('tipo')=="Sedex") selected @endif>Sedex</option>
                                    <option value="Sedex_10" @if(old('tipo')=="Sedex10") selected @endif>Sedex 10</option>
                                </select>
                                @if ($errors->has('tipo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <br/>
                        <div class="row">

                            <div class="col-md-4 @if($errors->has('pasta')) has-error @endif">
                                <label>* Pasta</label>
                                <input type="text" class="form-control" name="pasta" maxlength="7" 
                                placeholder="Digite a pasta aqui" value="{{ old('pasta') }}" required>
                                @if ($errors->has('pasta'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pasta') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-8 @if($errors->has('cliente')) has-error @endif">
                                <label>* Cliente</label>
                                <input type="text" class="form-control" name="cliente" maxlength="191" 
                                placeholder="Digite o cliente aqui" value="{{ old('cliente') }}" required>
                                @if ($errors->has('cliente'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cliente') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <br/>
                        <div class="row">

                            <div class="col-md-6 @if($errors->has('destinatario')) has-error @endif">
                                <label>* Destinatário</label>
                                <input type="text" class="form-control" name="destinatario" maxlength="191" 
                                placeholder="Digite o destinatário aqui" value="{{ old('destinatario') }}" required>
                                @if ($errors->has('destinatario'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('destinatario') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6 @if($errors->has('ac')) has-error @endif">
                                <label>* Aos cuidados de</label>
                                <input type="text" class="form-control" name="ac" maxlength="191" 
                                placeholder="Digite o A/C aqui" value="{{ old('ac') }}" required>
                                @if ($errors->has('ac'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('ac') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <br/>
                        <div class="row">

                            <div class="col-md-12 @if($errors->has('descricao')) has-error @endif">
                                <label>* Descrição/Título</label>
                                <input type="text" class="form-control" name="descricao" maxlength="191" 
                                placeholder="Digite a descrição aqui" value="{{ old('descricao') }}" required>
                                @if ($errors->has('descricao'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('descricao') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <hr/>
                        <div class="row">

                            <div class="col-md-3 @if($errors->has('cep')) has-error @endif">
                                <label>* CEP</label>
                                <input type="text" class="form-control" name="cep" maxlength="10" 
                                placeholder="Digite o CEP aqui" value="{{ old('cep') }}" required>
                                @if ($errors->has('cep'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cep') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6 @if($errors->has('rua')) has-error @endif">
                                <label>* Rua</label>
                                <input type="text" class="form-control" name="rua" maxlength="191" 
                                placeholder="Digite a rua aqui" value="{{ old('rua') }}" required>
                                @if ($errors->has('rua'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rua') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 @if($errors->has('numero')) has-error @endif">
                                <label>* Número</label>
                                <input type="text" class="form-control" name="numero" maxlength="9" 
                                placeholder="Digite o número aqui" value="{{ old('numero') }}" required>
                                @if ($errors->has('numero'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('numero') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <br/>
                        <div class="row">

                            <div class="col-md-3 @if($errors->has('complemento')) has-error @endif">
                                <label>Complemento</label>
                                <input type="text" class="form-control" name="complemento" maxlength="191" 
                                placeholder="Digite o complemento aqui" value="{{ old('complemento') }}">
                                @if ($errors->has('complemento'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('complemento') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-6 @if($errors->has('cidade')) has-error @endif">
                                <label>* Cidade</label>
                                <input type="text" class="form-control" name="cidade" maxlength="191" 
                                placeholder="Digite a cidade aqui" value="{{ old('cidade') }}" required>
                                @if ($errors->has('cidade'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cidade') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-3 @if($errors->has('estado')) has-error @endif">
                                <label>* Estado</label>
                                <select class="form-control" name="estado" required>
                                    <option value="">Selecione um estado</option>
                                    <option value="AC" @if(old('estado')=="AC") selected @endif>Acre</option>
                                    <option value="AL" @if(old('estado')=="AL") selected @endif>Alagoas</option>
                                    <option value="AP" @if(old('estado')=="AP") selected @endif>Amapá</option>
                                    <option value="AM" @if(old('estado')=="AM") selected @endif>Amazonas</option>
                                    <option value="BA" @if(old('estado')=="BA") selected @endif>Bahia</option>
                                    <option value="CE" @if(old('estado')=="CE") selected @endif>Ceará</option>
                                    <option value="DF" @if(old('estado')=="DF") selected @endif>Distrito Federal</option>
                                    <option value="ES" @if(old('estado')=="ES") selected @endif>Espírito Santo</option>
                                    <option value="GO" @if(old('estado')=="GO") selected @endif>Goiás</option>
                                    <option value="MA" @if(old('estado')=="MA") selected @endif>Maranhão</option>
                                    <option value="MT" @if(old('estado')=="MT") selected @endif>Mato Grosso</option>
                                    <option value="MS" @if(old('estado')=="MS") selected @endif>Mato Grosso do Sul</option>
                                    <option value="MG" @if(old('estado')=="MG") selected @endif>Minas Gerais</option>
                                    <option value="PA" @if(old('estado')=="PA") selected @endif>Pará</option>
                                    <option value="PB" @if(old('estado')=="PB") selected @endif>Paraíba</option>
                                    <option value="PR" @if(old('estado')=="PR") selected @endif>Paraná</option>
                                    <option value="PE" @if(old('estado')=="PE") selected @endif>Pernambuco</option>
                                    <option value="PI" @if(old('estado')=="PI") selected @endif>Piauí</option>
                                    <option value="RJ" @if(old('estado')=="RJ") selected @endif>Rio de Janeiro</option>
                                    <option value="RN" @if(old('estado')=="RN") selected @endif>Rio Grande do Norte</option>
                                    <option value="RS" @if(old('estado')=="RS") selected @endif>Rio Grande do Sul</option>
                                    <option value="RO" @if(old('estado')=="RO") selected @endif>Rondônia</option>
                                    <option value="RR" @if(old('estado')=="RR") selected @endif>Roraima</option>
                                    <option value="SC" @if(old('estado')=="SC") selected @endif>Santa Catarina</option>
                                    <option value="SP" @if(old('estado')=="SP") selected @endif>São Paulo</option>
                                    <option value="SE" @if(old('estado')=="SE") selected @endif>Sergipe</option>
                                    <option value="TO" @if(old('estado')=="TO") selected @endif>Tocantins</option>
                                </select>
                                @if ($errors->has('estado'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('estado') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                        <hr/>
                        <div class="row">

                            <div class="col-md-8 @if($errors->has('anexo')) has-error @endif">
                                <label>** Anexo/Documento</label>
                                <input type="file" class="form-control" name="anexo" required>
                                <small>** PDF e tamanho máximo 10 MB.</small>
                                @if ($errors->has('anexo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('anexo') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">
                                    <i class="glyphicon glyphicon-send"></i>
                                    Enviar
                                </button>
                            </div>

                        </div>
                        <br/>
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
                    <h2>Enviando...<br/>Aguarde.</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

@push ('scripts')
    <script src="{{ asset('assets/js/correios.js') }}"></script>
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>

    @if(old('reembolsavel')=="0")
        <script>
            toggleInputMotivo("0");
            $("[name='motivo']").val("{{ old('motivo') }}");
        </script>
    @endif
    
@endpush


@endsection
