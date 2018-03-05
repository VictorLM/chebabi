@extends ('site.templates.template')

@section('conteudo')

<div id="conteudo">
    <!-- Trabalhe Conosco -->
    <h1 id="trabalhe">TRABALHE CONOSCO</h1>
    <div id="contatomain">
        
        @if(Session::has('alert-success'))
            <div style="text-align: center;background-color: lightgreen;width: 50%;margin: auto;border-radius: 1em;line-height: 3em;margin-bottom: 1em;">
                <li>{{ Session::get('alert-success') }}</li>
            </div>
        @elseif(Session::has('alert-error'))
            <div style="text-align: center;background-color: #ff44008c;width: 50%;margin: auto;border-radius: 1em;line-height: 3em;margin-bottom: 1em;">
                <li>{{ Session::get('alert-error') }}</li>
            </div>
        @endif
        
        <div class="contato">
            <div id="curriculotxt">
                <p>Se você tem interesse em trabalhar ou estagiar conosco, favor preencher nosso formulário e anexar seu currículo. 
                   Nossa equipe de seleção irá analisá-lo  e poderá contatá-lo para uma entrevista pessoal.</p>
            </div>
            <div id="form">
                <form enctype="multipart/form-data" id="form" name="form" method="POST" action="{{action('Site\SiteController@enviar_curriculo')}}">
                    {!! csrf_field() !!}
                    <fieldset>
                        <br/>
                            <p><input type="text" id="nomeid" placeholder="* Nome" name="remetenteNome" required="required" maxlength="50" 
                                value="{{old('remetenteNome')}}" @if($errors->has('remetenteNome')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('remetenteNome'))
                                <small style="color:red;">
                                    {{ $errors->first('remetenteNome') }}
                                </small>
                            @endif
                            <p><input type="text" id="foneid" class="sp_celphones" placeholder="* Telefone" name="telefone" required="required" maxlength="17" 
                                value="{{old('telefone')}}" @if($errors->has('telefone')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('telefone'))
                                <small style="color:red;">
                                    {{ $errors->first('telefone') }}
                                </small>
                            @endif
                            <p><input type="email" id="emailid" placeholder="* seunome@email.com" name="remetenteEmail" required="required" maxlength="50" 
                                value="{{old('remetenteEmail')}}" @if($errors->has('remetenteEmail')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('remetenteEmail'))
                                <small style="color:red;">
                                    {{ $errors->first('remetenteEmail') }}
                                </small>
                            @endif
                            <p><input type="text" id="rg" placeholder="* RG" name="rg" required="required" maxlength="13" 
                                value="{{old('rg')}}" @if($errors->has('rg')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('rg'))
                                <small style="color:red;">
                                    {{ $errors->first('rg') }}
                                </small>
                            @endif
                            <p><input type="text" id="cpf" placeholder="* CPF" name="cpf" required="required" maxlength="14" 
                                value="{{old('cpf')}}" @if($errors->has('cpf')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('cpf'))
                                <small style="color:red;">
                                    {{ $errors->first('cpf') }}
                                </small>
                            @endif
                            <p><input type="text" id="endereco" placeholder="* Endereço" name="endereco" required="required" maxlength="100" 
                                value="{{old('endereco')}}" @if($errors->has('endereco')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('endereco'))
                                <small style="color:red;">
                                    {{ $errors->first('endereco') }}
                                </small>
                            @endif
                            <p><input type="date" name="data" id="data" placeholder="* Data de nascimento" size="10" required="required" 
                                value="{{old('data')}}" @if($errors->has('data')) style="border-color:red;" autofocus @endif/></p>
                            @if ($errors->has('data'))
                                <small style="color:red;">
                                    {{ $errors->first('data') }}
                                </small>
                            @endif
                            <p><textarea placeholder="* Mensagem" name="mensagem" maxlength="2000" 
                                @if($errors->has('mensagem')) style="border-color:red;" autofocus @endif>{{old('mensagem')}}</textarea></p>
                            @if ($errors->has('mensagem'))
                                <small style="color:red;">
                                    {{ $errors->first('mensagem') }}
                                </small>
                                <br/>
                            @endif
                            <div id="areadiv">
                                <p>* Selecione a área desejada: 
                                    <select name="area" required="required" @if($errors->has('area')) style="border-color:red;" autofocus @endif>
                                        <option value=""></option>
                                        <option value="Área Administrativa" @if(old('area')=='Área Administrativa') selected @endif>Área Administrativa</option>
                                        <option value="Advogado Cível" @if(old('area')=='Advogado Cível') selected @endif>Advogado Cível</option>
                                        <option value="Advogado Trabalhista" @if(old('area')=='Advogado Trabalhista') selected @endif>Advogado Trabalhista</option>
                                        <option value="Apoio Jurídico" @if(old('area')=='Apoio Jurídico') selected @endif>Apoio Jurídico</option>
                                        <option value="Estágio Cível" @if(old('area')=='Estágio Cível') selected @endif>Estágio Cível</option>
                                        <option value="Estágio Trabalhista" @if(old('area')=='Estágio Trabalhista') selected @endif>Estágio Trabalhista</option>
                                        <option value="Financeiro" @if(old('area')=='Financeiro') selected @endif>Financeiro</option>
                                    </select>
                                </p>
                                @if ($errors->has('area'))
                                    <small style="color:red;">
                                        {{ $errors->first('area') }}
                                    </small>
                                    <br/>
                                @endif
                            </div>
                            <div id="curriculo" @if($errors->has('curriculo')) style="border-color:red;" autofocus @endif>
                                <p>Anexe seu currículo (<strong>SOMENTE PDF E MÁXIMO 5MB</strong>): 
                                    <label class="fileContainer">
                                        ANEXAR CURRICULO
                                        <input type="file" name="curriculo"/>
                                    </label>
                                </p>
                                <br/>
                                @if ($errors->has('curriculo'))
                                    <small style="color:red;">
                                        {{ $errors->first('curriculo') }}
                                    </small>
                                    <br/>
                                @endif
                            </div>
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
                            </br>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <!-- Fim Trabalhe Conosco -->
</div>

@push ('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
    <script src="{{ asset('assets/js/tel_mask.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush

@endsection
