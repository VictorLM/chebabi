@extends ('site.templates.template')

@section('conteudo')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Trabalhe conosco</li>
        </ol>
    </nav>

    <div class="card bg-light mb-3">
        <div class="card-body">

            <h1 class="text-center">TRABALHE CONOSCO</h1>
            <p>
                Se você tem interesse em trabalhar ou estagiar conosco, 
                preencha o formulário abaixo e anexe seu currículo. 
                Nossa equipe de seleção irá analisar as informações enviadas 
                e poderá contatá-lo(a) para uma entrevista pessoal.
            </p>
            <hr/>
            @if(Session::has('alert-success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('alert-success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @elseif(Session::has('alert-error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('alert-error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @elseif(Session::has('errors'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <form enctype="multipart/form-data" method="POST" id="form" action="{{action('Site\SiteController@enviar_curriculo')}}">
                {!! csrf_field() !!}
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>* Nome</label>
                        <input type="text" name="nome" value="{{old('nome')}}" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" placeholder="* Nome" maxlength="50" required>
                        @if ($errors->has('nome'))
                            <div class="invalid-feedback">{{ $errors->first('nome') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>* E-mail</label>
                        <input type="email" value="{{old('email')}}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="* E-mail" maxlength="100" required>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>* Telefone</label>
                        <input type="text" value="{{old('telefone')}}" class="form-control sp_celphones{{ $errors->has('telefone') ? ' is-invalid' : '' }}" name="telefone" placeholder="* Telefone" maxlength="17" required>
                        @if ($errors->has('telefone'))
                            <div class="invalid-feedback">{{ $errors->first('telefone') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label>* RG</label>
                        <input type="text" value="{{old('rg')}}" class="form-control{{ $errors->has('rg') ? ' is-invalid' : '' }}" name="rg" placeholder="* RG" maxlength="13" required>
                        @if ($errors->has('rg'))
                            <div class="invalid-feedback">{{ $errors->first('rg') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label>* CPF</label>
                        <input type="text" value="{{old('cpf')}}" class="form-control{{ $errors->has('cpf') ? ' is-invalid' : '' }}" name="cpf" placeholder="* CPF" maxlength="14" required>
                        @if ($errors->has('cpf'))
                            <div class="invalid-feedback">{{ $errors->first('cpf') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>* Endereço</label>
                        <input type="text" name="endereco" value="{{old('endereco')}}" class="form-control{{ $errors->has('endereco') ? ' is-invalid' : '' }}" placeholder="* Endereço" maxlength="100" required>
                        @if ($errors->has('endereco'))
                            <div class="invalid-feedback">{{ $errors->first('endereco') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <label>* Nascimento</label>
                        <input type="date" value="{{old('nascimento')}}" class="form-control{{ $errors->has('nascimento') ? ' is-invalid' : '' }}" name="nascimento" placeholder="* Data de nascimento" required>
                        @if ($errors->has('nascimento'))
                            <div class="invalid-feedback">{{ $errors->first('nascimento') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
                        <label>* Área</label>
                        <select class="custom-select{{ $errors->has('area') ? ' is-invalid' : '' }}" name="area" required>
                            <option value=""></option>
                            <option value="Área Administrativa" @if(old('area')=='Área Administrativa') selected @endif>Área Administrativa</option>
                            <option value="Advogado(a) Cível" @if(old('area')=='Advogado(a) Cível') selected @endif>Advogado(a) Cível</option>
                            <option value="Advogado(a) Trabalhista" @if(old('area')=='Advogado(a) Trabalhista') selected @endif>Advogado(a) Trabalhista</option>
                            <option value="Apoio Jurídico" @if(old('area')=='Apoio Jurídico') selected @endif>Apoio Jurídico</option>
                            <option value="Estágio Cível" @if(old('area')=='Estágio Cível') selected @endif>Estágio Cível</option>
                            <option value="Estágio Trabalhista" @if(old('area')=='Estágio Trabalhista') selected @endif>Estágio Trabalhista</option>
                            <option value="Financeiro" @if(old('area')=='Financeiro') selected @endif>Financeiro</option>
                        </select>
                        @if ($errors->has('area'))
                            <div class="invalid-feedback">{{ $errors->first('area') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
                        <label>* Currículo</label>
                        <div class="custom-file{{ $errors->has('curriculo') ? ' is-invalid' : '' }}">
                            <input type="file" class="custom-file-input" name="curriculo">
                            <label class="custom-file-label">Anexar currículo</label>
                        </div>
                        @if ($errors->has('curriculo'))
                            <div class="invalid-feedback">{{ $errors->first('curriculo') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-4">
                        {!! NoCaptcha::display() !!}
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>* Mensagem</label>
                        <textarea class="form-control{{ $errors->has('mensagem') ? ' is-invalid' : '' }}" placeholder="* Mensagem (máximo 2000 caracteres)" name="mensagem" maxlength="2000">{{old('mensagem')}}</textarea>
                        @if ($errors->has('mensagem'))
                            <div class="invalid-feedback">{{ $errors->first('mensagem') }}</div>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-paper-plane"></i> Enviar</button>
                <br/>
                <small>* Campos obrigatórios.</small>

            </form>

        </div>
    </div>

    <!-- Modal -->

    <div class="modal fade" id="loaderModal">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h2>Enviando...Aguarde.</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>

@push ('scripts')
    <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('assets/js/tel_mask.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>
@endpush

@endsection
