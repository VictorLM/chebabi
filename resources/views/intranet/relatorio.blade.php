@extends('intranet.templates.template')

@push ('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
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
                        </div>
                        <div class="panel-body">
                            
                            <div class="row">
                                <div class="col-md-4 @if($errors->has('usuario')) has-error @endif">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Responsável: 
                                    </label>
                                    <input type="text" id="usuario" class="form-control" name="usuario" 
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
                                    <select name="kilometragem" id="tipo_viagem" class="form-control" form="form_relatorio" required="required">
                                        <option value="">Selecione uma opção</option>
                                        <option value="1" @if(old('tipo_viagem') == '1') selected @endif>Com kilometragem</option>
                                        <option value="0" @if(old('tipo_viagem') == '0') selected @endif>Sem kilometragem</option>
                                    </select>
                                    @if ($errors->has('tipo_viagem'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('tipo_viagem') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 reemb @if($errors->has('reembolsavel')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Reembolsável: </label>
                                    <select name="reembolsavel" class="form-control" id="reembolsavel" form="form_relatorio" required="required">
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
                            INFORMAÇÕES DO CLIENTE
                        </div>
                        <div class="panel-body">
                            
                            <div class="row">
                                <div class="col-md-4 @if($errors->has('pasta1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número da Pasta: </label>
                                    <i id="loaderpasta1" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" class="form-control" id="pasta1" name="pasta1" maxlength="11"  value="{{old('pasta1')}}" required="required">
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
                                    <input type="text" id="cliente1" class="form-control" name="cliente1" maxlength="100" value="{{old('cliente1')}}" required="required">
                                    @if ($errors->has('cliente1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cliente1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 @if($errors->has('contrario1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Parte contrária: </label>
                                    <i id="loadercontrario1" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                    <input type="text" id="contrario1" class="form-control" name="contrario1" maxlength="100" value="{{old('contrario1')}}" required="required">
                                    @if ($errors->has('contrario1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contrario1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('proc1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Número do Processo: </label>
                                    <input type="text" id="proc1" class="form-control" name="proc1" maxlength="30" value="{{old('proc1')}}" required="required">
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
                                    <input type="text" id="cliente2" class="form-control" maxlength="100" value="{{old('cliente2')}}" name="cliente2">
                                    @if ($errors->has('cliente2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cliente2') }}</strong>
                                        </span>
                                    @endif
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
                                    <input type="text" id="cliente3" class="form-control" maxlength="100" value="{{old('cliente3')}}" name="cliente3">
                                    @if ($errors->has('cliente3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cliente3') }}</strong>
                                        </span>
                                    @endif
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
                        
                        <div class="panel-footer text-right">
                            <button id="del-cliente" type="button" class="btn btn-danger">
                                <i class="glyphicon glyphicon-trash"></i> Remover
                            </button>
                            <button id="add-cliente" type="button" class="btn btn-primary">
                                <i class="glyphicon glyphicon-plus"></i> Adicionar
                            </button>
                            <br/>
                            <small>* Máximo 3.</small>
                        </div>
                        
                    </div>
                    <!-- Viagem -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            INFORMAÇÕES DA VIAGEM
                        </div>
                        <div class="panel-body">
                            
                            <div class="row infos_viagem">
                                <div class="col-md-6 @if($errors->has('enda')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço A - Origem: </label>
                                    <input type="text" class="form-control" id="enda" name="end1" maxlength="150" value="{{old('enda')}}" placeholder="Endereço de origem" required="required">
                                    @if ($errors->has('enda'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('enda') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('endb')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço B - Destino: </label>
                                    <input type="text" class="form-control" id="endb" name="end2" maxlength="150" value="{{old('endb')}}" placeholder="Endereço de destino" required="required">
                                    @if ($errors->has('endb'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('endb') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
            
                            <div class="row infos_viagem">
                                <div class="col-md-6 @if($errors->has('endc')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Endereço C - Segundo destino ou retorno: </label>
                                    <input type="text" class="form-control calcend" id="endc" name="end3" maxlength="150" value="{{old('endc')}}" placeholder="Segundo destino ou retorno" required="required">
                                    @if ($errors->has('endc'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('endc') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('endd')) has-error @endif">
                                    <label>Endereço D - Retorno: </label>
                                    <input type="text" class="form-control calcend" id="endd" name="end4" maxlength="150" value="{{old('endd')}}" placeholder="Endereço de retorno">
                                    @if ($errors->has('endd'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('endd') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-md-3 infos_viagem @if($errors->has('totalkm')) has-error @endif">
                                    <label>
                                        <i class="glyphicon glyphicon-asterisk"></i> 
                                        <i class="glyphicon glyphicon-transfer"></i> 
                                        Total Km: 
                                    </label>
                                    <input type="number" class="form-control" id="totalkm" name="totalkm" required="required" maxlength="6" value="{{old('totalkm')}}" placeholder="Se um valor nao aparecer aqui, atualize a página" readonly>
                                    @if ($errors->has('totalkm'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('totalkm') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="data col-md-6 @if($errors->has('data')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Data da viagem: </label>
                                    <input type="date" class="form-control calcdespesas calcend" name="data" id="data" placeholder="DD/MM/AAAA" size="10" value="{{old('data')}}" required="required">
                                    @if ($errors->has('data'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('data') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 @if($errors->has('motivoviagem1')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Descrição de viagem - Cliente 1: </label>
                                    <input type="text" class="form-control calcdespesas calcend" name="motivoviagem1" id="motivoviagem1" maxlength="100" value="{{old('motivoviagem1')}}" placeholder="OBRIGATÓRIO - Descrição de viagem 1" required="required">
                                    @if ($errors->has('motivoviagem1'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('motivoviagem1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 descricao-viagem2 @if($errors->has('motivoviagem2')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Descrição de viagem - Cliente 2: </label>
                                    <input type="text" class="form-control" name="motivoviagem2" id="motivoviagem2" maxlength="100" value="{{old('motivoviagem2')}}" placeholder="OBRIGATÓRIO - Descrição de viagem 2">
                                    @if ($errors->has('motivoviagem2'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('motivoviagem2') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-6 descricao-viagem3 @if($errors->has('motivoviagem3')) has-error @endif">
                                    <label><i class="glyphicon glyphicon-asterisk"></i> Descrição de viagem - Cliente 3: </label>
                                    <input type="text" class="form-control" name="motivoviagem3" id="motivoviagem3" maxlength="100" value="{{old('motivoviagem3')}}" placeholder="OBRIGATÓRIO - Descrição de viagem 3">
                                    @if ($errors->has('motivoviagem3'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('motivoviagem3') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- Despesas gerais -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            DESPESAS GERAIS
                        </div>
                        <div class="panel-body">
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
                                    <input type="text" class="form-control" maxlength="100" value="{{old('clientedespesa1')}}" name="clientedespesa1">
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
                                    <input type="text" class="form-control" maxlength="100" value="{{old('clientedespesa2')}}" name="clientedespesa2">
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
                                    <input type="text" class="form-control" maxlength="100" value="{{old('clientedespesa3')}}" name="clientedespesa3">
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
                                    <input type="text" class="form-control" maxlength="100" value="{{old('clientedespesa4')}}" name="clientedespesa4">
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
                        
                        <div class="panel-footer text-right">
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
                        </div>
                        <div class="panel-body">
                            
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
                            <div class="row">
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
                    </div>
                    
                    <div class="row">
                        <!-- Observações -->
                        <div class="col-md-6 @if($errors->has('observacoes')) has-error @endif">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    OBSERVAÇÕES
                                </div>
                                <div class="panel-body">
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
                                </div>
                                <div class="panel-body">
                                    <input type="file" class="form-control" id="comprovantes" name="comprovantes" />
                                    @if ($errors->has('comprovantes'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('comprovantes') }}</strong>
                                        </span>
                                    @endif
                                    <small>* * * SOMENTE PDF E ARQUIVO ÚNICO * * *</small>
                                </div>
                            </div>
                        </div>
                        
                    </div>
            
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <br/>
                            <button type="submit" class="btn btn-primary btn-lg" id="enviar" name="enviar"><i class="glyphicon glyphicon-send"></i> Enviar relatório</button>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSIlzZS-4ZmvJPtY6wIO3U3ggrswyTyqY"async defer></script>
    <script src="{{ asset('assets/js/js.js') }}"></script>
@endpush

@endsection