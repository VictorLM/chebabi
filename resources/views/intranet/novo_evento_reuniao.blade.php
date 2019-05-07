@extends('intranet.templates.template')

@push('styles')
    <link rel="stylesheet" href="{{url("assets/css/multi-select.dist.css")}}"/>
    <link href="{{asset('assets/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.min.css" rel="stylesheet">
@endpush

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet/agenda')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Evento - Reunião
            </h2>
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

            <form method="POST" id="form" action="{{action('APIs\MicrosoftController@criar_evento')}}">
                {!! csrf_field() !!}

                <div class="form-group col-md-4">
                    <label for="tipo">Tipo</label>
                    <select class="form-control" id="tipo" name="tipo" required>
                        <option value="Reunião" selected>Reunião</option>
                    </select>
                    @if ($errors->has('tipo'))
                    <small style="color:red;">
                            <strong>{{ $errors->first('tipo') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-4">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" maxlength="200" value="{{old('titulo')}}" required @if($errors->has('titulo')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('titulo'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('titulo') }}</strong>
                        </small>
                    @endif
                </div>

                <div class="form-group col-md-4">
                    <label for="local">Local</label>
                    <select class="form-control" id="local" name="local" required>
                        <option value=""></option>
                        <option value="Sala 1" @if(old('local')=='Sala 1') selected @endif>Sala 1</option>
                        <option value="Sala 2" @if(old('local')=='Sala 2') selected @endif>Sala 2</option>
                        <option value="Outro">Outro</option>
                    </select>
                    <input type="text" class="form-control" name="" id="local_text" maxlength="100" value="{{old('local')}}" style="display:none;" placeholder="Preencha o local" @if($errors->has('local')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('local'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('local') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-2">
                    <label for="iniciodata">Início - data</label>
                    <input type="date" class="form-control" name="iniciodata" id="iniciodata" value="{{old('iniciodata')}}" required @if($errors->has('iniciodata')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('iniciodata'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('iniciodata') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-2">
                    <label for="iniciohora">Início - hora</label>
                    <input type="time" class="form-control" name="iniciohora" id="iniciohora" value="{{old('iniciohora')}}" required @if($errors->has('iniciohora')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('iniciohora'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('iniciohora') }}</strong>
                        </small>
                    @endif
                </div>
    
                <div class="form-group col-md-2">
                    <label for="terminodata">Término - dia</label>
                    <input type="date" class="form-control" name="terminodata" id="terminodata" value="{{old('terminodata')}}" required @if($errors->has('terminodata')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('terminodata'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('terminodata') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-2">
                    <label for="terminohora">Término - hora</label>
                    <input type="time" class="form-control" name="terminohora" id="terminohora" value="{{old('terminohora')}}" required @if($errors->has('terminohora')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('terminohora'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('terminohora') }}</strong>
                        </small>
                    @endif
                </div>

                <div class="form-group col-md-4">
                    <label for="recorrencia">Recorrência</label>
                    <select multiple="multiple" class="form-control" name="recorrencia[]" id="recorrencia-select" style="width:90%;" @if($errors->has('recorrencia')) style="border-color:red;" @endif>
                        <option value="">Evento único</option>
                        <option value="Monday" {{(collect(old('recorrencia'))->contains("Monday")) ? 'selected':''}}>Toda segunda-feira</option>
                        <option value="Tuesday" {{(collect(old('recorrencia'))->contains("Tuesday")) ? 'selected':''}}>Toda terça-feira</option>
                        <option value="Wednesday" {{(collect(old('recorrencia'))->contains("Wednesday")) ? 'selected':''}}>Toda quarta-feira</option>
                        <option value="Thursday" {{(collect(old('recorrencia'))->contains("Thursday")) ? 'selected':''}}>Toda quinta-feira</option>
                        <option value="Friday" {{(collect(old('recorrencia'))->contains("Friday")) ? 'selected':''}}>Toda sexta-feira</option>
                    </select>
                    @if ($errors->has('recorrencia'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('recorrencia') }}</strong>
                        </small>
                    @endif
                </div>

                
                <div id="agendados" class="form-group col-md-12">
                    <h3 id="h-tbl-agendados" class="text-center h3-top-margin-5"></h3>
                    <table id="tabela_agendados" class="table table-bordered table-hover">
                        <!-- APPEND AJAX AQUI -->
                    </table>
                </div>
                    
                <div class="form-group col-md-12">
                    <label for="envolvidos">Envolvidos</label>
                    <select class="form-control" multiple="multiple" id="my-select" name="envolvidos[]" @if($errors->has('envolvidos')) style="border-color:red;" autofocus @endif>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{(collect(old('envolvidos'))->contains($user->id)) ? 'selected':''}}>{{$user->name}}</option>
                    @endforeach
                    </select>
                    <small>** O criador do evento é envolvido automaticamente.</small><br/>
                    <small>* Máximo 60 envolvidos.</small><br/>
                    @if ($errors->has('envolvidos'))
                        <small style="color:red;">
                            {{ $errors->first('envolvidos') }}
                        </small>
                        <br/>
                    @endif
                </div>
                
                <div class="form-group col-md-12">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" name="descricao" maxlength="2000" 
                    @if($errors->has('descricao')) style="border-color:red;" autofocus @endif>{{old('descricao')}}</textarea>
                    @if ($errors->has('descricao'))
                        <small style="color:red;">
                            {{ $errors->first('descricao') }}
                        </small>
                        <br/>
                    @endif
                </div>
    
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" style="margin-left: 1em;"><i class="glyphicon glyphicon-send"></i> Cadastrar</button>
                    </div>
                </div>
    
            </form>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/pt-br.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multiple-select/1.2.0/multiple-select.min.js"></script>
    <script src="{{url('assets/js/novo_evento_reuniao.js')}}"></script>
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>
    @if((!empty(old('iniciodata')) && !empty(old('local'))) || (!empty($evento)))
        @if(old('local') == "Sala 1" || old('local') == "Sala 2")
            <script>
                eventos_reuniao("{{old('iniciodata')}}","{{old('local')}}");
            </script>
        @elseif(!empty($evento->local) && ($evento->local == "Sala 1" || $evento->local == "Sala 2"))
            <script>
                eventos_reuniao(Carbon\Carbon::parse($evento->start)->format('Y-m-d')),"$evento->local");
            </script>
        @endif
    @endif
@endpush
    
@endsection


