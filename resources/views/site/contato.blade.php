@extends ('site.templates.template')

@section('conteudo')

<div id="conteudo">
    <!-- Contato -->
    <h1>CONTATO</h1>
    <div id="contatomain">
        
        @if(Session::has('alert-success'))
            <script>
                alert('{{ Session::get('alert-success') }}');
            </script>
        @endif
                
        <div class="contato">
            <div id="form">
                <form id="form" name="form" method="POST" action="{{action('Site\SiteController@enviar_contato')}}">
                    {!! csrf_field() !!}
                    <fieldset>
                        <legend>Entre em contato conosco</legend>
                        <p><input type="text" id="nomeid" placeholder="* Nome" required="required" name="remetenteNome" maxlength="50" 
                            value="{{old('remetenteNome')}}" @if($errors->has('remetenteNome')) style="border-color:red;" autofocus @endif/></p>
                        @if ($errors->has('remetenteNome'))
                            <small style="color:red;">
                                {{ $errors->first('remetenteNome') }}
                            </small>
                        @endif
                        <p><input type="text" class="sp_celphones" id="foneid" placeholder="Telefone" name="telefone" maxlength="17" 
                            value="{{old('telefone')}}" @if($errors->has('telefone')) style="border-color:red;" autofocus @endif/></p>
                        @if ($errors->has('telefone'))
                            <small style="color:red;">
                                {{ $errors->first('telefone') }}
                            </small>
                        @endif
                        <p><input type="email" id="emailid" placeholder="* seunome@email.com" name="remetenteEmail" 
                            value="{{old('remetenteEmail')}}" @if($errors->has('remetenteEmail')) style="border-color:red;" autofocus @endif/></p>
                        @if ($errors->has('remetenteEmail'))
                            <small style="color:red;">
                                {{ $errors->first('remetenteEmail') }}
                            </small>
                        @endif
                        <p><textarea placeholder="* Mensagem (máximo 2000 caracteres)" name="mensagem" maxlength="2000" 
                            @if($errors->has('mensagem')) style="border-color:red;" autofocus @endif>{{old('mensagem')}}</textarea></p>
                        @if ($errors->has('mensagem'))
                            <small style="color:red;">
                                {{ $errors->first('mensagem') }}
                            </small>
                            <br/>
                        @endif
                        <div id="captcha" @if($errors->has('g-recaptcha-response')) style="border-style: solid; border-width: 1px; border-color:red;" autofocus @endif>
                            {!! NoCaptcha::display() !!}
                        </div>
                        @if ($errors->has('g-recaptcha-response'))
                            <small style="color:red;">
                                {{ $errors->first('g-recaptcha-response') }}
                            </small>
                            <br/>
                        @endif
                        <input type="submit" value="ENVIAR" id="enviaemail" name="enviarFormulario" />
                        <p>Trabalhe conosco - <a style="color: blue;" href="{{url('/trabalhe-conosco#conteudo')}}">Clique aqui para enviar seu currículo.</a></p>
                        </br>
                        <p>Fone/Fax: (19) 3237-3747 - (19) 3203-4744</p>
                        </br>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- Fim Contato -->
</div>

@push ('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('assets/js/tel_mask.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush

@endsection