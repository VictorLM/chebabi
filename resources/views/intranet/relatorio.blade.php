@php

    $old_infos_gerais = false;
    $error_infos_clientes = false;
    $error_infos_viagem = false;
    $error_infos_despesas = false;
    $error_infos_valores = false;
    $error_infos_obs = false;
    $error_infos_comprovantes = false;

    if($errors->has('usuario') || $errors->has('kilometragem') || 
    $errors->has('reembolsavel') || $errors->has('carro') || $errors->has('pedagio')){
        $old_infos_gerais = true;
    }else if(!$errors->any()){
        $old_infos_gerais = true;
    }

    if($errors->has('pasta1') || $errors->has('cliente1') || $errors->has('contrario1') || $errors->has('proc1') || 
        $errors->has('pasta2') || $errors->has('cliente2') || $errors->has('contrario2') || $errors->has('proc2') || 
        $errors->has('pasta3') || $errors->has('cliente3') || $errors->has('contrario3') || $errors->has('proc3')){

        $error_infos_clientes = true;
    }

    if($errors->has('end1') || $errors->has('end2') || $errors->has('end3') || $errors->has('end4') || $errors->has('end5') || $errors->has('totalkm') || 
        $errors->has('data') || $errors->has('motivoviagem1') || $errors->has('motivoviagem2') || $errors->has('motivoviagem3')){

        $error_infos_viagem = true;
    }

    if($errors->has('descricaodespesa1') || $errors->has('descricaodespesa2') || $errors->has('descricaodespesa3') || $errors->has('descricaodespesa4') || 
        $errors->has('despesapasta1') || $errors->has('despesapasta2') || $errors->has('despesapasta3') || $errors->has('despesapasta4') || 
        $errors->has('clientedespesa1') || $errors->has('clientedespesa2') || $errors->has('clientedespesa3') || $errors->has('clientedespesa4') || 
        $errors->has('despesasgerais1') || $errors->has('despesasgerais2') || $errors->has('despesasgerais3') || $errors->has('despesasgerais4')){

        $error_infos_despesas = true;
    }

    if($errors->has('caucao') || $errors->has('valorkm') || $errors->has('totalgastos') || $errors->has('aserdevolvido')){

        $error_infos_valores = true;
    }

    if($errors->has('observacoes')){

        $error_infos_obs = true;
    }

    if($errors->has('comprovantes')){

        $error_infos_comprovantes = true;
    }

@endphp

@extends('intranet.templates.template')

@push ('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css"/>
@endpush

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Relatório de viagem</h2>
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

                <form enctype="multipart/form-data" method="POST" id="form_relatorio" action="{{action('Relatorio\RelatorioController@create')}}">
                    {!! csrf_field() !!}

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            INFORMAÇÕES GERAIS
                            @if($old_infos_gerais)
                                <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-gerais"></i>
                            @else
                                <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-gerais"></i>
                            @endif
                        </div>
                        <div class="panel-body{{$old_infos_gerais ? '' : ' esconder'}}" id="infos-gerais">
                            
                            <div class="row">
                                <div class="col-md-4 @if($errors->has('usuario')) has-error @endif">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Responsável: 
                                    </label>
                                    <input type="text" id="usuario" class="form-control required" name="usuario" 
                                    placeholder="Se seu nome não aparecer aqui, atualize a página." 
                                    required="required" value="{{Auth::user()->name}}" readonly>
                                    @if ($errors->has('usuario'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('usuario') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 @if($errors->has('kilometragem')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Tipo de viagem: </label>
                                    <select name="kilometragem" id="tipo_viagem" class="form-control required" form="form_relatorio" required="required">
                                        <option value="">Selecione uma opção</option>
                                        <option value="1" @if(old('kilometragem') == '1') selected @endif>Com kilometragem</option>
                                        <option value="0" @if(old('kilometragem') == '0') selected @endif>Sem kilometragem</option>
                                    </select>
                                    @if ($errors->has('kilometragem'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('kilometragem') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 reemb @if($errors->has('reembolsavel')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Reembolsável: </label>
                                    <select name="reembolsavel" class="form-control required" id="reembolsavel" form="form_relatorio" required="required">
                                        <option value="">Selecione uma opção</option>
                                        <option value="1" @if(old('reembolsavel') == "1") selected @endif>Sim</option>
                                        <option value="0" @if(old('reembolsavel') == "0")) selected @endif>Não</option>
                                    </select>
                                    @if ($errors->has('reembolsavel'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('reembolsavel') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div id="infos_gerais_append" class="row">
                                <div class="col-md-12 carro @if($errors->has('carro')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Veículo: </label>
                                    <select name="carro" class="gerais_requireds form-control" id="carro" form="form_relatorio">
                                        <option value="">Selecione uma opção</option>
                                        <option value="Escritório" @if(old('carro') == "Escritório") selected @endif>Escritório</option>
                                        <option value="Particular" @if(old('carro') == "Particular") selected @endif>Particular</option>
                                    </select>
                                    @if ($errors->has('carro'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('carro') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 pedagio @if($errors->has('pedagio')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Pedágio: </label>
                                    <select name="pedagio" class="pedagio_required form-control" id="pedagio" form="form_relatorio">
                                        <option value="">Selecione uma opção</option>
                                        <option value="1" @if(old('pedagio') == "1") selected @endif>Sim</option>
                                        <option value="0" @if(old('pedagio') == "0") selected @endif>Não</option>
                                    </select>
                                    @if ($errors->has('pedagio'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pedagio') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- Cliente -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            INFORMAÇÕES DOS CLIENTES
                            @if($error_infos_clientes)
                                <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-clientes"></i>
                            @else
                                <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-clientes"></i>
                            @endif
                        </div>
                        <div class="panel-body infos-clientes{{$error_infos_clientes ? '' : ' esconder'}}">
                            
                            <div class="row">
                                <div class="col-md-4 @if($errors->has('pasta1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número da Pasta: </label>
                                    <i id="loaderpasta1" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" class="form-control required" id="pasta1" name="pasta1" maxlength="11"  value="{{old('pasta1')}}" required="required">
                                    @if ($errors->has('pasta1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pasta1') }}</strong>
                                        </span>
                                    @endif
                                    <div id="display1" class="live-search list-group">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Puxar do Legal One: </label>
                                    <button class="pesquisar btn btn-primary btn-block" value="1" type="button">Pesquisar Pasta</button>
                                </div>
                                <div class="col-md-6 @if($errors->has('cliente1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Cliente: </label>
                                    <i id="loadercliente1" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="cliente1" class="form-control cliente required" name="cliente1" maxlength="100" value="{{old('cliente1')}}" required="required" autocomplete="off" @if(!empty(old('cliente1'))) readonly @endif>
                                    @if ($errors->has('cliente1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cliente1') }}</strong>
                                        </span>
                                    @endif
                                    <div id="display-cliente1" class="live-search list-group">
                                    </div>
                                    <input type="hidden" id="cliente-id1" name="cliente-id1" value="{{old('cliente-id1')}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 @if($errors->has('contrario1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Parte contrária: </label>
                                    <i id="loadercontrario1" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="contrario1" class="form-control required" name="contrario1" maxlength="100" value="{{old('contrario1')}}" required="required">
                                    @if ($errors->has('contrario1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contrario1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('proc1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número do Processo: </label>
                                    <input type="text" id="proc1" class="form-control required" name="proc1" maxlength="30" value="{{old('proc1')}}" required="required">
                                    @if ($errors->has('proc1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('proc1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row clientes2 @if(old('pasta2') != "" || old('cliente2') != "" || old('contrario2') != "" || old('proc2') != "") block @endif">
                                <hr>
                                <div class="col-md-4 @if($errors->has('pasta2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número da Pasta: </label>
                                    <i id="loaderpasta2" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" class="form-control" id="pasta2" maxlength="11" value="{{old('pasta2')}}" name="pasta2">
                                    @if ($errors->has('pasta2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pasta2') }}</strong>
                                        </span>
                                    @endif
                                    <div id="display2" class="live-search list-group">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Puxar do Legal One: </label>
                                    <button class="pesquisar btn btn-primary btn-block" value="2" type="button">Pesquisar Pasta</button>
                                </div>
                                <div class="col-md-6 @if($errors->has('cliente2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Cliente: </label>
                                    <i id="loadercliente2" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="cliente2" class="form-control cliente" maxlength="100" value="{{old('cliente2')}}" name="cliente2"  autocomplete="off" @if(!empty(old('cliente2'))) readonly @endif>
                                    @if ($errors->has('cliente2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cliente2') }}</strong>
                                        </span>
                                    @endif
                                    <div id="display-cliente2" class="live-search list-group">
                                    </div>
                                    <input type="hidden" id="cliente-id2" name="cliente-id2" value="{{old('cliente-id2')}}">
                                </div>
                            </div>
                            <div class="row clientes2 @if(old('pasta2') != "" || old('cliente2') != "" || old('contrario2') != "" || old('proc2') != "") block @endif">
                                <div class="col-md-6 @if($errors->has('contrario2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Parte contrária: </label>
                                    <i id="loadercontrario2" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="contrario2" class="form-control" maxlength="100" value="{{old('contrario2')}}" name="contrario2">
                                    @if ($errors->has('contrario2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contrario2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('proc2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número do Processo: </label>
                                    <input type="text" id="proc2" class="form-control" maxlength="30" value="{{old('proc2')}}" name="proc2">
                                    @if ($errors->has('proc2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('proc2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row clientes3 @if(old('pasta3') != "" || old('cliente3') != "" || old('contrario3') != "" || old('proc3') != "") block @endif">
                                <hr>
                                <div class="col-md-4 @if($errors->has('pasta3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número da Pasta: </label>
                                    <i id="loaderpasta3" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" class="form-control" id="pasta3" maxlength="11" value="{{old('pasta3')}}" name="pasta3">
                                    @if ($errors->has('pasta3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pasta3') }}</strong>
                                        </span>
                                    @endif
                                    <div id="display3" class="live-search list-group">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label>Puxar do Legal One: </label>
                                    <button class="pesquisar btn btn-primary btn-block" value="3" type="button">Pesquisar Pasta</button>
                                </div>
                                <div class="col-md-6 @if($errors->has('cliente3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Cliente: </label>
                                    <i id="loadercliente3" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="cliente3" class="form-control cliente" maxlength="100" value="{{old('cliente3')}}" name="cliente3"  autocomplete="off" @if(!empty(old('cliente3'))) readonly @endif>
                                    @if ($errors->has('cliente3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cliente3') }}</strong>
                                        </span>
                                    @endif
                                    <div id="display-cliente3" class="live-search list-group">
                                    </div>
                                    <input type="hidden" id="cliente-id3" name="cliente-id3" value="{{old('cliente-id3')}}">
                                </div>
                            </div>
                            <div class="row clientes3 @if(old('pasta3') != "" || old('cliente3') != "" || old('contrario3') != "" || old('proc3') != "") block @endif">
                                <div class="col-md-6 @if($errors->has('contrario3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Parte contrária: </label>
                                    <i id="loadercontrario3" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="contrario3" class="form-control" maxlength="100" value="{{old('contrario3')}}" name="contrario3">
                                    @if ($errors->has('contrario3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contrario3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('proc3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número do Processo: </label>
                                    <input type="text" id="proc3" class="form-control" maxlength="30" value="{{old('proc3')}}" name="proc3">
                                    @if ($errors->has('proc3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('proc3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
            
                        </div>
                        
                        <div class="panel-footer infos-clientes text-right{{ $error_infos_clientes ? '' : ' esconder' }}">
                            <button id="finaliza-clientes" type="button" class="btn btn-success float-left">
                                <i class="glyphicon glyphicon-ok"></i> Finalizar
                            </button>
                            <button id="del-cliente" type="button" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i> Remover
                            </button>
                            <button id="add-cliente" type="button" class="btn btn-primary">
                                <i class="glyphicon glyphicon-plus"></i> Adicionar
                            </button>
                            <br/><br/>
                            <small>* Máximo 3.</small>
                        </div>
                        
                    </div>
                    <!-- Viagem -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            INFORMAÇÕES DA VIAGEM
                            @if($error_infos_viagem)
                                <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-viagem"></i>
                            @else
                                <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-viagem"></i>
                            @endif
                        </div>
                        <div class="panel-body infos-viagem{{$error_infos_viagem ? '' : ' esconder'}}">
                            
                            <div class="row infos_viagem">
                                <div class="col-md-6 @if($errors->has('end1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço 1 - Origem: </label>
                                    <input type="text" class="form-control" id="enda" name="end1" maxlength="150" value="{{old('end1')}}" placeholder="Endereço de origem" required="required" autocomplete="off">
                                    @if ($errors->has('end1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('end1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('end2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço 2: </label>
                                    <input type="text" class="form-control" id="endb" name="end2" maxlength="150" value="{{old('end2')}}" placeholder="Endereço de destino" required="required">
                                    @if ($errors->has('end2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('end2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
            
                            <div class="row infos_viagem">
                                <div class="col-md-12 @if($errors->has('end3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço 3: </label>
                                    <input type="text" class="form-control calcend" id="endc" name="end3" maxlength="150" value="{{old('end3')}}" placeholder="Segundo destino ou retorno" required="required">
                                    @if ($errors->has('end3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('end3') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="col-md-12 endereco4 @if($errors->has('end4')) has-error @endif  @if(empty(old('end4'))) esconder @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço 4: </label>
                                    <input type="text" class="form-control calcend" id="endd" name="end4" maxlength="150" value="{{old('end4')}}" placeholder="Terceiro destino ou retorno">
                                    @if ($errors->has('end4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('end4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row infos_viagem">
                                <div class="col-md-12 endereco5 @if($errors->has('end5')) has-error @endif @if(empty(old('end5'))) esconder @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço 5: </label>
                                    <input type="text" class="form-control calcend" id="ende" name="end5" maxlength="150" value="{{old('end5')}}" placeholder="Endereço de retorno">
                                    @if ($errors->has('end5'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('end5') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row infos_viagem text-right" style="padding: 10px 15px;">
                                <button id="del-endereco" type="button" class="btn btn-danger">
                                    <i class="glyphicon glyphicon-trash"></i> Remover
                                </button>
                                <button id="add-endereco" type="button" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-plus"></i> Adicionar
                                </button>
                                <br/>
                                <small>* Máximo 5, mínimo 3.</small>
                            </div>

                            <hr style="margin-top: 0px;margin-bottom: 5px"/>

                            <div class="row">
                                <div class="col-md-3 infos_viagem @if($errors->has('totalkm')) has-error @endif">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Total Km: 
                                    </label>
                                    <input type="number" class="form-control" id="totalkm" name="totalkm" required="required" maxlength="6" value="{{old('totalkm')}}" placeholder="Calculado automáticamente" readonly>
                                    @if ($errors->has('totalkm'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('totalkm') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="data col-md-6 @if($errors->has('data')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Data da viagem: </label>
                                    <input type="date" class="form-control calcdespesas calcend required" name="data" id="data" placeholder="DD/MM/AAAA" size="10" value="{{old('data')}}" required="required">
                                    @if ($errors->has('data'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('data') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('motivoviagem1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Descrição de viagem - <span id="descricao_cliente1">Cliente 1</span>: </label>
                                    <input type="text" class="form-control calcdespesas calcend required" name="motivoviagem1" id="motivoviagem1" maxlength="100" value="{{old('motivoviagem1')}}" placeholder="OBRIGATÓRIO - Descrição de viagem" required="required">
                                    @if ($errors->has('motivoviagem1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('motivoviagem1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 descricao-viagem2 @if($errors->has('motivoviagem2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Descrição de viagem - <span id="descricao_cliente2">Cliente 2</span>: </label>
                                    <input type="text" class="form-control" name="motivoviagem2" id="motivoviagem2" maxlength="100" value="{{old('motivoviagem2')}}" placeholder="OBRIGATÓRIO - Descrição de viagem">
                                    @if ($errors->has('motivoviagem2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('motivoviagem2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 descricao-viagem3 @if($errors->has('motivoviagem3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Descrição de viagem - <span id="descricao_cliente3">Cliente 3</span>: </label>
                                    <input type="text" class="form-control" name="motivoviagem3" id="motivoviagem3" maxlength="100" value="{{old('motivoviagem3')}}" placeholder="OBRIGATÓRIO - Descrição de viagem">
                                    @if ($errors->has('motivoviagem3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('motivoviagem3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>

                        <div class="panel-footer infos-viagem text-left{{ $error_infos_viagem ? '' : ' esconder' }}">
                            <button id="finaliza-viagem" type="button" class="btn btn-success">
                                <i class="glyphicon glyphicon-ok"></i> Finalizar
                            </button>
                        </div>
                    </div>
                    <!-- Despesas gerais -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            DESPESAS GERAIS
                            @if($error_infos_despesas)
                                <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-despesas"></i>
                            @else
                                <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-despesas"></i>
                            @endif
                        </div>
                        <div class="panel-body infos-despesas{{$error_infos_despesas ? '' : ' esconder'}}">
                            <!-- Despesas 1 -->
                            <div class="row">
                                <div class="col-md-6 @if($errors->has('descricaodespesa1')) has-error @endif">
                                    <label>Descrição: </label>
                                    <input type="text" class="form-control" name="descricaodespesa1" maxlength="100" value="{{old('descricaodespesa1')}}" placeholder="Estacionamento, Alimentação, Cópias, etc.">
                                    @if ($errors->has('descricaodespesa1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descricaodespesa1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesapasta1')) has-error @endif">
                                    <label>Pasta: </label>
                                    <input type="text" class="form-control" maxlength="11" value="{{old('despesapasta1')}}" name="despesapasta1">
                                    @if ($errors->has('despesapasta1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesapasta1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 @if($errors->has('clientedespesa1')) has-error @endif">
                                    <label>Cliente: </label>
                                    
                                    <select name="clientedespesa1" class="form-control clientes-despesas" form="form_relatorio">
                                        @if (!empty(old('clientedespesa1')))
                                            <option value="{{old('clientedespesa1')}}" selected>{{old('clientedespesa1')}}</option>
                                        @else
                                            <option value="" selected></option>
                                        @endif
                                    </select>

                                    @if ($errors->has('clientedespesa1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('clientedespesa1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesasgerais1')) has-error @endif">
                                    <label>Custo (R$) </label>
                                    <input type="number" class="form-control calcdespesas" id="despesasgerais1" maxlength="7" value="{{old('despesasgerais1')}}" name="despesasgerais1" step="any">
                                    @if ($errors->has('despesasgerais1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesasgerais1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Despesas 2 -->
                            <div class="row despesas2 @if(old('despesasgerais2') != "" || old('descricaodespesa2') != "" || old('despesapasta2') != "" || old('clientedespesa2') != "") block @endif">
                                <hr>
                                <div class="col-md-6 @if($errors->has('descricaodespesa2')) has-error @endif">
                                    <label>Descrição: </label>
                                    <input type="text" class="form-control" name="descricaodespesa2" maxlength="100" value="{{old('descricaodespesa2')}}" placeholder="Estacionamento, Alimentação, Cópias, etc.">
                                    @if ($errors->has('descricaodespesa2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descricaodespesa2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesapasta2')) has-error @endif">
                                    <label>Pasta: </label>
                                    <input type="text" class="form-control" maxlength="11" value="{{old('despesapasta2')}}" name="despesapasta2">
                                    @if ($errors->has('despesapasta2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesapasta2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row despesas2 @if(old('despesasgerais2') != "" || old('descricaodespesa2') != "" || old('despesapasta2') != "" || old('clientedespesa2') != "") block @endif">
                                <div class="col-md-6 @if($errors->has('clientedespesa2')) has-error @endif">
                                    <label>Cliente: </label>
                                    <select name="clientedespesa2" class="form-control clientes-despesas" form="form_relatorio">
                                        @if (!empty(old('clientedespesa2')))
                                            <option value="{{old('clientedespesa2')}}" selected>{{old('clientedespesa2')}}</option>
                                        @else
                                            <option value="" selected></option>
                                        @endif
                                    </select>
                                    @if ($errors->has('clientedespesa2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('clientedespesa2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesasgerais2')) has-error @endif">
                                    <label>Custo (R$) </label>
                                    <input type="number" class="form-control calcdespesas" id="despesasgerais2" maxlength="7" value="{{old('despesasgerais2')}}" name="despesasgerais2" step="any">
                                    @if ($errors->has('despesasgerais2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesasgerais2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Despesas 3 -->
                            <div class="row despesas3 @if(old('despesasgerais3') != "" || old('descricaodespesa3') != "" || old('despesapasta3') != "" || old('clientedespesa3') != "") block @endif">
                                <hr>
                                <div class="col-md-6 @if($errors->has('descricaodespesa3')) has-error @endif">
                                    <label>Descrição: </label>
                                    <input type="text" class="form-control" name="descricaodespesa3" maxlength="100" value="{{old('descricaodespesa3')}}" placeholder="Estacionamento, Alimentação, Cópias, etc.">
                                    @if ($errors->has('descricaodespesa3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descricaodespesa3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesapasta3')) has-error @endif">
                                    <label>Pasta: </label>
                                    <input type="text" class="form-control" maxlength="11" value="{{old('despesapasta3')}}" name="despesapasta3">
                                    @if ($errors->has('despesapasta3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesapasta3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row despesas3 @if(old('despesasgerais3') != "" || old('descricaodespesa3') != "" || old('despesapasta3') != "" || old('clientedespesa3') != "") block @endif">
                                <div class="col-md-6 @if($errors->has('clientedespesa3')) has-error @endif">
                                    <label>Cliente: </label>
                                    <select name="clientedespesa3" class="form-control clientes-despesas" form="form_relatorio">
                                        @if (!empty(old('clientedespesa3')))
                                            <option value="{{old('clientedespesa3')}}" selected>{{old('clientedespesa3')}}</option>
                                        @else
                                            <option value="" selected></option>
                                        @endif
                                    </select>
                                    @if ($errors->has('clientedespesa3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('clientedespesa3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesasgerais3')) has-error @endif">
                                    <label>Custo (R$) </label>
                                    <input type="number" class="form-control calcdespesas" id="despesasgerais3" maxlength="7" value="{{old('despesasgerais3')}}" name="despesasgerais3" step="any">
                                    @if ($errors->has('despesasgerais3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesasgerais3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- Despesas 4 -->
                            <div class="row despesas4 @if(old('despesasgerais4') != "" || old('descricaodespesa4') != "" || old('despesapasta4') != "" || old('clientedespesa4') != "") block @endif">
                                <hr>
                                <div class="col-md-6 @if($errors->has('descricaodespesa4')) has-error @endif">
                                    <label>Descrição: </label>
                                    <input type="text" class="form-control" name="descricaodespesa4" maxlength="100" value="{{old('descricaodespesa4')}}" placeholder="Estacionamento, Alimentação, Cópias, etc.">
                                    @if ($errors->has('descricaodespesa4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('descricaodespesa4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesapasta4')) has-error @endif">
                                    <label>Pasta: </label>
                                    <input type="text" class="form-control" maxlength="11" value="{{old('despesapasta4')}}" name="despesapasta4">
                                    @if ($errors->has('despesapasta4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesapasta4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row despesas4 @if(old('despesasgerais4') != "" || old('descricaodespesa4') != "" || old('despesapasta4') != "" || old('clientedespesa4') != "") block @endif">
                                <div class="col-md-6 @if($errors->has('clientedespesa4')) has-error @endif">
                                    <label>Cliente: </label>
                                    <select name="clientedespesa4" class="form-control clientes-despesas" form="form_relatorio">
                                        @if ($errors->has('clientedespesa4'))
                                            <option value="{{old('clientedespesa4')}}" selected>{{old('clientedespesa4')}}</option>
                                        @else
                                            <option value="" selected></option>
                                        @endif
                                    </select>
                                    @if ($errors->has('clientedespesa4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('clientedespesa4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('despesasgerais4')) has-error @endif">
                                    <label>Custo (R$) </label>
                                    <input type="number" class="form-control calcdespesas" id="despesasgerais4" maxlength="7" value="{{old('despesasgerais4')}}" name="despesasgerais4" step="any">
                                    @if ($errors->has('despesasgerais4'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('despesasgerais4') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
            
                            
                        </div>
                        
                        <div class="panel-footer text-right infos-despesas{{$error_infos_despesas ? '' : ' esconder'}}">
                            <button id="finaliza-despesas" type="button" class="btn btn-success float-left">
                                <i class="glyphicon glyphicon-ok"></i> Finalizar
                            </button>
                            <button id="del-despesa" type="button" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i> Remover
                            </button>
                            <button id="add-despesa" type="button" class="btn btn-primary">
                                <i class="glyphicon glyphicon-plus"></i> Adicionar
                            </button>
                            <br/>
                            <small>* Máximo 4.</small>
                        </div>
                        
                    </div>
                    <!-- Valores -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            VALORES
                            @if($error_infos_valores)
                                <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-valores"></i>
                            @else
                                <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-valores"></i>
                            @endif
                        </div>
                        <div class="panel-body infos-valores{{$error_infos_valores ? '' : ' esconder'}}">
                            
                            <div class="row">
                                <div class="caucao col-md-12 @if($errors->has('caucao')) has-error @endif">
                                    <label>Valor caução (R$): </label>
                                    <input type="number" class="form-control" id="caucao" value="{{old('caucao')}}" name="caucao" step="any">
                                    @if ($errors->has('caucao'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('caucao') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="infos_viagem col-md-6">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Valor Km (R$): 
                                    </label>
                                    <input type="number" class="form-control" id="valorkm" value="{{old('valorkm')}}" name="valorkm" required="required" readonly>
                                    @if ($errors->has('valorkm'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('valorkm') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row" id="gastos-aserdevolvido">
                                <div class="col-md-6">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Valor total dos gastos (R$): </label>
                                    <input type="number" class="form-control" id="totalgastos" value="{{old('totalgastos')}}" name="totalgastos" readonly>
                                    @if ($errors->has('totalgastos'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('totalgastos') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Valor a ser devolvido (R$): 
                                    </label>
                                    <input type="number" class="form-control" id="aserdevolvido" value="{{old('aserdevolvido')}}" name="aserdevolvido" required="required" readonly>
                                    @if ($errors->has('aserdevolvido'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('aserdevolvido') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>

                        <div class="panel-footer infos-valores text-left{{$error_infos_valores ? '' : ' esconder'}}">
                            <button id="finaliza-valores" type="button" class="btn btn-success">
                                <i class="glyphicon glyphicon-ok"></i> Finalizar
                            </button>
                        </div>

                    </div>
                    
                    <div class="row">
                        <!-- Observações -->
                        <div class="col-md-6 @if($errors->has('observacoes')) has-error @endif">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    OBSERVAÇÕES
                                    @if($error_infos_obs)
                                        <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-obs"></i>
                                    @else
                                        <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-obs"></i>
                                    @endif
                                </div>
                                <div class="panel-body{{$error_infos_obs ? '' : ' esconder'}}" id="infos-obs">
                                    <textarea name="observacoes" id="obs" class="form-control" maxlength="200" placeholder="Observações">{{old('observacoes')}}</textarea>
                                    @if ($errors->has('observacoes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('observacoes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Comprovantes -->
                        <div class="col-md-6 @if($errors->has('comprovantes')) has-error @endif">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    COMPROVANTES
                                    @if($error_infos_comprovantes)
                                        <i class="glyphicon glyphicon-minus text-right float-right" id="control-infos-comprovantes"></i>
                                    @else
                                        <i class="glyphicon glyphicon-plus text-right float-right" id="control-infos-comprovantes"></i>
                                    @endif
                                </div>
                                <div class="panel-body{{$error_infos_comprovantes ? '' : ' esconder'}}" id="infos-comprovantes">
                                    <input type="file" class="form-control" id="comprovantes" name="comprovantes" />
                                    @if ($errors->has('comprovantes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('comprovantes') }}</strong>
                                        </span>
                                    @endif
                                    <small>* * * SOMENTE PDF E ARQUIVO ÚNICO DE NO MÁXIMO 2MB * * *</small>
                                </div>
                            </div>
                        </div>
                        
                    </div>
            
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <br/>
                            <button type="submit" class="btn btn-primary btn-lg{{$errors->any() ? '' : ' esconder'}}" id="enviar" name="enviar"><i class="glyphicon glyphicon-send"></i> Enviar relatório</button>
                            <br/>
                            <p class="text-left"><i class="glyphicon glyphicon-asterisk"></i> Os campos com este símbolo são obrigatórios.</p>
                            <p class="text-left"><i class="glyphicon glyphicon-transfer"></i> Os campos com este símbolo são calculados/preenchidos automaticamente.</p>
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
                    <h2>Enviando..Aguarde.</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>

</div>

@push ('scripts')
    <script src="{{ asset('assets/js/relatorio_viagem.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSIlzZS-4ZmvJPtY6wIO3U3ggrswyTyqY&libraries=places&callback=initAutocomplete" async defer></script>
@endpush

@endsection