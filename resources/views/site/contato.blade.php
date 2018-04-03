@extends ('site.templates.template')

@section('conteudo')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contato</li>
        </ol>
    </nav>

    <div class="card bg-light mb-3">
        <div class="card-body">

            <h1 class="text-center">CONTATO</h1>
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

            <div class="text-center">
                <h3>Nôs siga nas redes sociais</h3>
                <a href="https://www.linkedin.com/company/izique-chebabi-advogados-associados" target="_blank" class="fa fa-linkedin fa-2x social"></a>
                <a href="https://www.facebook.com/Izique-Chebabi-Advogados-Associados-346767155816975" target="_blank" class="fa fa-facebook fa-2x social"></a>
                <a href="#" class="fa fa-youtube fa-2x social"></a>
                <a href="https://plus.google.com/+IziqueChebabiAdvogadosAssociadosCampinas" target="_blank" class="fa fa-google fa-2x social"></a>
            </div>

            <hr/>

            <form method="POST" id="form" action="{{action('Site\SiteController@enviar_contato')}}">
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
                    <div class="form-group col-md-6">
                        <label>Telefone</label>
                        <input type="text" value="{{old('telefone')}}" class="form-control sp_celphones{{ $errors->has('telefone') ? ' is-invalid' : '' }}" name="telefone" placeholder="Telefone" maxlength="17">
                        @if ($errors->has('telefone'))
                            <div class="invalid-feedback">{{ $errors->first('telefone') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6 text-right">
                        {!! NoCaptcha::display() !!}
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>* Mensagem</label>
                        <textarea class="form-control{{ $errors->has('mensagem') ? ' is-invalid' : '' }}" placeholder="* Mensagem (máximo 2000 caracteres)" name="mensagem" maxlength="2000" required>{{old('mensagem')}}</textarea>
                        @if ($errors->has('mensagem'))
                            <div class="invalid-feedback">{{ $errors->first('mensagem') }}</div>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-paper-plane"></i> Enviar</button>
                <br/>
                <small>* Campos obrigatórios.</small>
                <hr/>
                <div>
                    <span>Trabalhe conosco - <a href="http://www.chebabi.com/trabalhe-conosco">Clique aqui para enviar seu currículo.</a></span>
                    <br/>
                    <span>Fone/Fax: (19) 3237-3747 - (19) 3203-4744 - <a href="mailto:atendimento@chebabi.com">atendimento@chebabi.com</a></span>
                </div>

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
