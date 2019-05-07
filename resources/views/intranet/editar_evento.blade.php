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
                <a href="{{url('/intranet/agenda')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Editar Evento: {{$evento->title}} / Criador: {{$evento->organizador_nome}}
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

            <form method="POST" id="form" action="{{action('APIs\MicrosoftController@update_evento')}}">
                {!! csrf_field() !!}
                    
                <div class="form-group col-md-4">
                    <label for="tipo">Tipo</label>
                    <select class="form-control" name="tipo" required>
                        <option value=""></option>
                        <option value="Ausente" @if($evento->tipo == 'Ausente') selected @endif>Ausente</option>
                        <option value="Carro" @if($evento->tipo =='Carro') selected @endif>Carro</option>
                        <option value="Reunião" @if($evento->tipo =='Reunião') selected @endif>Reunião</option>
                        <option value="Outro" @if($evento->tipo =='Outro') selected @endif>Outro</option>
                    </select>
                    @if ($errors->has('tipo'))
                    <small style="color:red;">
                            <strong>{{ $errors->first('tipo') }}</strong>
                        </small>
                    @endif
                </div>

                <input type="hidden" name="id" value="{{$evento->id}}">
                
                <div class="form-group col-md-4">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" name="titulo" maxlength="200" value="{{$titulo}}" required @if($errors->has('titulo')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('titulo'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('titulo') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-4">
                    <label for="local">Local</label>
                    <input type="text" class="form-control" name="local" maxlength="100" value="{{$evento->local}}" required @if($errors->has('local')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('local'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('local') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-2">
                    <label for="iniciodata">Início - data</label>
                    <input type="date" class="form-control" name="iniciodata" id="iniciodata" value="{{Carbon\Carbon::parse($evento->start)->format('Y-m-d')}}" required @if($errors->has('iniciodata')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('iniciodata'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('iniciodata') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-2">
                    <label for="iniciohora">Início - hora</label>
                    <input type="time" class="form-control" name="iniciohora" id="iniciohora" value="{{Carbon\Carbon::parse($evento->start)->format('H:i')}}" required @if($errors->has('iniciohora')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('iniciohora'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('iniciohora') }}</strong>
                        </small>
                    @endif
                </div>
    
                <div class="form-group col-md-2">
                    <label for="terminodata">Término - dia</label>
                    <input type="date" class="form-control" name="terminodata" id="terminodata" value="{{Carbon\Carbon::parse($evento->end)->format('Y-m-d')}}" required @if($errors->has('terminodata')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('terminodata'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('terminodata') }}</strong>
                        </small>
                    @endif
                </div>
                
                <div class="form-group col-md-2">
                    <label for="terminohora">Término - hora</label>
                    <input type="time" class="form-control" name="terminohora" id="terminohora" value="{{Carbon\Carbon::parse($evento->end)->format('H:i')}}" required @if($errors->has('terminohora')) style="border-color:red;" autofocus @endif>
                    @if ($errors->has('terminohora'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('terminohora') }}</strong>
                        </small>
                    @endif
                </div>

                <div class="form-group col-md-4">
                    <label for="terminohora">Recorrência</label>
                    <select multiple="multiple" class="form-control" name="recorrencia[]" id="recorrencia-select" style="width:90%;" @if($errors->has('recorrencia')) style="border-color:red;" @endif>
                        <option value="">Evento único</option>
                        <option value="Monday" @if(!empty(unserialize($evento->dow)) && in_array(1, unserialize($evento->dow))) selected @endif>Toda segunda-feira</option>
                        <option value="Tuesday" @if(!empty(unserialize($evento->dow)) && in_array(2, unserialize($evento->dow))) selected @endif>Toda terça-feira</option>
                        <option value="Wednesday" @if(!empty(unserialize($evento->dow)) && in_array(3, unserialize($evento->dow))) selected @endif>Toda quarta-feira</option>
                        <option value="Thursday" @if(!empty(unserialize($evento->dow)) && in_array(4, unserialize($evento->dow))) selected @endif>Toda quinta-feira</option>
                        <option value="Friday" @if(!empty(unserialize($evento->dow)) && in_array(5, unserialize($evento->dow))) selected @endif>Toda sexta-feira</option>
                    </select>
                    @if ($errors->has('recorrencia'))
                        <small style="color:red;">
                            <strong>{{ $errors->first('recorrencia') }}</strong>
                        </small>
                    @endif
                </div>
                    
                <div class="form-group col-md-12">
                    <label for="envolvidos">Envolvidos</label>
                    <select class="form-control" multiple="multiple" id="my-select" name="envolvidos[]" @if($errors->has('envolvidos')) style="border-color:red;" autofocus @endif>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" {{(collect($envolvidos)->contains($user->email)) ? 'selected':''}}>{{$user->name}}</option>
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
                    @if($errors->has('descricao')) style="border-color:red;" autofocus @endif>{{strip_tags($evento->descricao)}}</textarea>
                    @if ($errors->has('descricao'))
                        <small style="color:red;">
                            {{ $errors->first('descricao') }}
                        </small>
                        <br/>
                    @endif
                </div>
    
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" style="margin-left: 1em;"><i class="glyphicon glyphicon-send"></i> Atualizar</button>
                    </div>
                </div>
    
            </form>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <h2>Atualizando...<br/>Aguarde.</h2>
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
    <script src="{{url('assets/js/novoevento.js')}}"></script>
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>
@endpush
    
@endsection


