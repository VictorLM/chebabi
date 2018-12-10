
@extends('layouts.app')

@push('styles')
    <link href="{{asset('assets/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (empty($cliente))
                        <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Cliente</h2>
                    @else
                        <h2><a href="{{url('/intranet/admin/clientes')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Editar Cliente</h2>
                    @endif
                    <small>Os Clientes são exibidos na seção "Clientes" dentro da Intranet.</small><br/>
                    <small>Para vincular um Advogado responsável é necessário primeiro te-lô cadastrado como Advogado.</small>
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
                    
                    @if (!empty($cliente))
                        <form class="form-horizontal" method="POST" id="form" enctype="multipart/form-data" action="{{action('Admin\AdminController@update_cliente', $cliente->id)}}">
                        {{ csrf_field() }}
                    @else
                        <form class="form-horizontal" method="POST" id="form" enctype="multipart/form-data" action="{{action('Admin\AdminController@novo_cliente')}}">
                        {{ csrf_field() }}
                    @endif

                    <div class="row">

                        <div class="col-md-6">
                            <label>* Nome</label>
                            <input type="text" class="form-control{{$errors->has('nome') ? ' has-error' : ''}}" name="nome" maxlength="200" value="{{$cliente->nome or old('nome')}}" placeholder="Nome Cliente Ltda." required>
                            @if ($errors->has('oab'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('oab') }}</strong>
                                </span>
                            @endif
                        </div>
                            
                        <div class="col-md-6">
                            <label>Logo</label>
                            <input type="file" class="form-control{{$errors->has('logo') ? ' has-error' : ''}}" name="logo">
                            <small>*** TAMANHO MÁXIMO 300KB.</small>
                            @if ($errors->has('logo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('logo') }}</strong>
                                </span>
                            @endif
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <label>Advogado(s) Cível(is) (Máximo 3)</label>
                            @if (empty($cliente))
                                <select multiple="multiple" class="form-control advs-select" name="adv_civel[]" style="width:90%;" @if($errors->has('adv_civel')) style="border-color:red;" @endif>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" {{(collect(old('adv_civel'))->contains($user->id)) ? 'selected':''}}>{{$user->name}}</option>
                                    @endforeach
                                </select>
                            @else
                                <select multiple="multiple" class="form-control advs-select" name="adv_civel[]" style="width:90%;" @if($errors->has('adv_civel')) style="border-color:red;" @endif>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" @if($cliente->adv_civel_1 == $user->id || $cliente->adv_civel_2 == $user->id || $cliente->adv_civel_3 == $user->id ) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if ($errors->has('adv_civel'))
                                <small style="color:red;">
                                    <strong>{{ $errors->first('adv_civel') }}</strong>
                                </small>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label>Advogado(s) Trabalhista(s) (Máximo 3)</label>
                            @if (empty($cliente))
                                <select multiple="multiple" class="form-control advs-select" name="adv_trab[]" style="width:90%;" @if($errors->has('adv_trab')) style="border-color:red;" @endif>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" {{(collect(old('adv_trab'))->contains($user->id)) ? 'selected':''}}>{{$user->name}}</option>
                                    @endforeach
                                </select>
                            @else
                                <select multiple="multiple" class="form-control advs-select" name="adv_trab[]" style="width:90%;" @if($errors->has('adv_trab')) style="border-color:red;" @endif>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" @if($cliente->adv_trab_1 == $user->id || $cliente->adv_trab_2 == $user->id || $cliente->adv_trab_3 == $user->id ) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if ($errors->has('adv_trab'))
                                <small style="color:red;">
                                    <strong>{{ $errors->first('adv_trab') }}</strong>
                                </small>
                            @endif
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Empresas (Separe-as com ponto e vírgula ;)</label>
                            @php
                                $empresas = [];
                                if(!empty($cliente->empresas)){
                                    foreach(json_decode($cliente->empresas) as $empresa){
                                        $empresas[] = strip_tags($empresa)."; ";
                                    }
                                }else{
                                    if(!empty(old('empresas'))){
                                        $empresas[] = old('empresas');
                                    }
                                }
                            @endphp
                            <textarea class="form-control" name="empresas" maxlength="2000" placeholder="Empresa Um Ltda.; Empresa Dois ME." @if($errors->has('empresas')) style="border-color:red;" @endif>@foreach($empresas as $item){{$item}}@endforeach</textarea>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-lg btn-primary">
                                @if (empty($cliente))
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.min.js"></script>
    <script>
        $(".advs-select").multipleSelect({
            selectAll: false
        });
    </script>
@endpush

@endsection
