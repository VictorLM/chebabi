@extends('intranet.templates.template')

@push ('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 
    
        <div class="col-md-10 col-md-offset-1">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Gasto/Custo - Legal One</h2>
                    <small>As custas podem ser visualizados/inseridos no Legal One > Processos > Visualizar Pasta > Gastos.</small>
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
                    @elseif(Session::has('alert-success'))
                        <div class="alert alert-success alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <li>{{ Session::get('alert-success') }}</li>
                        </div>
                    @endif
                    
                    <form class="form-horizontal" id="form" method="POST" enctype="multipart/form-data" action="{{action('APIs\LegalOneController@inserir_custas')}}">
                        {{ csrf_field() }}

                        <div class="row form-group">

                            <div class="col-md-3 col-md-offset-1{{ $errors->has('pasta') ? ' has-error' : '' }}">
                                <label>** Pasta</label>
                                <input id="pasta" type="text" class="form-control text-uppercase" name="pasta" maxlength="11" value="{{old('pasta')}}" required>
                                <div id="display" class="live-search list-group"></div>
                                <input name="pastaid" id="pastaid" type="hidden" value="{{old('pastaid')}}">
                                @if ($errors->has('pasta'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pasta') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <label>Pesquisar Pasta</label>
                                <button class="pesquisar btn btn-primary btn-block" id="pesquisar" type="button">
                                    Pesquisar
                                    <i id="loaderpasta" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                </button>
                            </div>

                            <div class="col-md-5{{ $errors->has('data') ? ' has-error' : '' }}">
                                <label>* Data</label>
                                <input type="date" class="form-control" name="data" maxlength="10" value="{{ old('data') }}" required>
                                @if ($errors->has('data'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('data') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>

                        <div class="row form-group">
                                
                            <div class="col-md-5 col-md-offset-1{{ $errors->has('valor') ? ' has-error' : '' }}">
                                <label>* Valor</label>
                                <input type="number" class="form-control" name="valor" id="valor" maxlength="10" value="{{ old('valor') }}" style="color:red;" placeholder="0,00" required>
                                @if ($errors->has('valor'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('valor') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-5{{ $errors->has('tipo') ? ' has-error' : '' }}">
                                <label>
                                    <i class="glyphicon glyphicon-transfer"></i>
                                    * Tipo de gasto <i id="loadertipogasto" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                </label>
                                <select id="tipo" class="form-control" name="tipo" required>
                                </select>
                                @if ($errors->has('tipo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>

                        <div class="row form-group">
                                
                            <div class="col-md-5 col-md-offset-1{{ $errors->has('descricao') ? ' has-error' : '' }}">
                                <label>* Descrição</label><small> - máximo 200 caracteres</small>
                                <input type="text" class="form-control" name="descricao" maxlength="10" value="{{ old('descricao') }}" required>
                                @if ($errors->has('descricao'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('descricao') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-5{{ $errors->has('credor') ? ' has-error' : '' }}">
                                <label>
                                    * Credor <i id="loadercredor" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                </label>
                                <select id="credor" class="form-control" name="credor" required>
                                    <option></option>
                                </select>
                                @if ($errors->has('credor'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('credor') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-6 col-md-offset-1{{ $errors->has('obs') ? ' has-error' : '' }}">
                                <label>* Observações</label>
                                <select class="form-control" name="obs" required>
                                    <option></option>
                                    <option value="Enviado ao cliente" @if(old('tipo')=="Enviado ao cliente") selected @endif>Enviado ao cliente</option>
                                    <option value="Recolhido pelo escritório" @if(old('tipo')=="Recolhido pelo escritório") selected @endif>Recolhido pelo escritório</option>
                                </select>
                                @if ($errors->has('obs'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('obs') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label><br/></label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="glyphicon glyphicon-plus"></i> Inserir
                                </button>
                            </div>

                        </div>

                    </form>
                    <br/>
                    <div class="col-md-10">
                        <span>* Campos obrigatórios.</span><br/>
                        <span><i class="glyphicon glyphicon-transfer"></i> Os campos com este símbolo são preenchidos automaticamente.</span><br/>
                        <span>** É obrigatório realizar a pesquisa pela pasta desejada e seleciona-la na lista de resultados.</span><br/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <h2>Inserindo...Aguarde.</h2>
                        <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                    </div>
                </div>
            </div>
        </div>

</div>
@push ('scripts')
    <script src="{{ asset('assets/js/custas.js') }}"></script>
@endpush
@endsection
