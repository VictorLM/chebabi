@extends('intranet.templates.template')

@push ('styles')
    <link href="{{asset('assets/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Andamento - Legal One</h2>
                    <small>Os andamentos podem ser visualizados/inseridos no Legal One > Processos > Visualizar Pasta > Andamentos.</small>
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
                    
                    <form class="form-horizontal" id="form" method="POST" enctype="multipart/form-data" action="{{action('APIs\LegalOneController@inserir_andamentos')}}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('pasta') ? ' has-error' : '' }}">
                            <label for="pasta" class="col-md-3 control-label">** Pasta</label>

                            <div class="col-md-4">
                                <input id="pasta" type="text" class="form-control text-uppercase" name="pasta" maxlength="11" value="{{ old('pasta') }}" @if(!empty(old('pasta'))) disabled @endif required>
                                <div id="display" class="live-search list-group"></div>
                                <input name="pastaid" id="pastaid" type="hidden" value="{{old('pastaid')}}">
                                @if ($errors->has('pasta'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pasta') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <button class="pesquisar btn btn-primary btn-block" id="pesquisar" type="button">
                                    Pesquisar Pasta
                                    <i id="loaderpasta" class="loader fa fa-refresh fa-spin fa-lg fa-fw"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
                            <label for="tipo" class="col-md-3 control-label">* Tipo</label>

                            <div class="col-md-7">
                                <select class="form-control" name="tipo" id="tipo" required>
                                    <option></option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{$tipo->id}}" @if(old('tipo')==$tipo->id) selected @endif>{{$tipo->name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('tipo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tipo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('descricao') ? ' has-error' : '' }}">
                            <label for="descricao" class="col-md-3 control-label">* Descrição</label>

                            <div class="col-md-7">
                                <textarea class="form-control" name="descricao" id="descricao" maxlength="2000" 
                                @if($errors->has('descricao')) style="border-color:red;" autofocus @endif required>{{old('descricao')}}</textarea>
                                <small>* Máximo 2000 caracteres.</small>
                                @if ($errors->has('descricao'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('descricao') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-plus"></i> Inserir
                                </button>
                            </div>
                        </div>
                        <span>* Campos obrigatórios.</span><br/>
                        <span>** É obrigatório realizar a pesquisa pela pasta desejada e seleciona-la na lista de resultados.</span><br/>
                        <span>*** O andamento será inserido com a seguinte observação: "Andamento inserido através da intranet.".</span><br/><br/>
                        <span><b>Nota:</b> Se a pasta tiver uma letra no prefixo (<b>M</b>2521, <b>A</b>1234), no Legal One essa pasta foi migrada com um novo número.
                            É necessário buscar este novo número da pasta migrada no Legal One para conseguir inserir andamentos por aqui.</span>
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
                        <h2>Inserindo...Aguarde.</h2>
                        <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                    </div>
                </div>
            </div>
        </div>

</div>
@push ('scripts')
    <script src="{{ asset('assets/js/andamentos.js') }}"></script>
@endpush
@endsection
