@extends('intranet.templates.template')

@push('styles')
    <link rel="stylesheet" href="{{url("assets/css/multi-select.dist.css")}}"/>
@endpush

@section('content')

<div class="container">
    <h2>
        <a href="{{url('/intranet/agenda')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Novo Evento
    </h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="well well-lg">

        <form method="POST" action="{{action('APIs\MicrosoftController@criar_evento')}}">
            {!! csrf_field() !!}
            
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
                <label for="tipo">Tipo</label>
                <select class="form-control" name="tipo" required>
                    <option value=""></option>
                    <option value="Ausente" @if(old('tipo')=='Ausente') selected @endif>Ausente</option>
                    <option value="Carro" @if(old('tipo')=='Carro') selected @endif>Carro</option>
                    <option value="Motorista" @if(old('tipo')=='Motorista') selected @endif>Motorista</option>
                    <option value="Reunião" @if(old('tipo')=='Reunião') selected @endif>Reunião</option>
                    <option value="Outro" @if(old('tipo')=='Outro') selected @endif>Outro</option>
                </select>
                @if ($errors->has('tipo'))
                <small style="color:red;">
                        <strong>{{ $errors->first('tipo') }}</strong>
                    </small>
                @endif
            </div>
            
            <div class="form-group col-md-4">
                <label for="local">Local</label>
                <input type="text" class="form-control" name="local" maxlength="100" value="{{old('local')}}" required @if($errors->has('local')) style="border-color:red;" autofocus @endif>
                @if ($errors->has('local'))
                    <small style="color:red;">
                        <strong>{{ $errors->first('local') }}</strong>
                    </small>
                @endif
            </div>
            
            <div class="form-group col-md-3">
                <label for="iniciodata">Início - data</label>
                <input type="date" class="form-control" name="iniciodata" id="iniciodata" value="{{old('iniciodata')}}" required @if($errors->has('iniciodata')) style="border-color:red;" autofocus @endif>
                @if ($errors->has('iniciodata'))
                    <small style="color:red;">
                        <strong>{{ $errors->first('iniciodata') }}</strong>
                    </small>
                @endif
            </div>
            
            <div class="form-group col-md-3">
                <label for="iniciohora">Início - hora</label>
                <input type="time" class="form-control" name="iniciohora" id="iniciohora" value="{{old('iniciohora')}}" required @if($errors->has('iniciohora')) style="border-color:red;" autofocus @endif>
                @if ($errors->has('iniciohora'))
                    <small style="color:red;">
                        <strong>{{ $errors->first('iniciohora') }}</strong>
                    </small>
                @endif
            </div>

            <div class="form-group col-md-3">
                <label for="terminodata">Término - dia</label>
                <input type="date" class="form-control" name="terminodata" id="terminodata" value="{{old('terminodata')}}" required @if($errors->has('terminodata')) style="border-color:red;" autofocus @endif>
                @if ($errors->has('terminodata'))
                    <small style="color:red;">
                        <strong>{{ $errors->first('terminodata') }}</strong>
                    </small>
                @endif
            </div>
            
            <div class="form-group col-md-3">
                <label for="terminohora">Término - hora</label>
                <input type="time" class="form-control" name="terminohora" id="terminohora" value="{{old('terminohora')}}" required @if($errors->has('terminohora')) style="border-color:red;" autofocus @endif>
                @if ($errors->has('terminohora'))
                    <small style="color:red;">
                        <strong>{{ $errors->first('terminohora') }}</strong>
                    </small>
                @endif
            </div>
              
            <div class="form-group col-md-12">
              <label for="envolvidos">Envolvidos</label>
                <select class="form-control" multiple="multiple" id="my-select" name="envolvidos[]" @if($errors->has('envolvidos')) style="border-color:red;" autofocus @endif>
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{ (collect(old('envolvidos'))->contains($user->id)) ? 'selected':'' }}>{{$user->name}}</option>
                @endforeach
              </select>
              <small>* Máximo 4.</small><br/>
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


@push ('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/pt-br.js"></script>
    <script src="{{url("assets/js/novoevento.js")}}"></script>
@endpush
    
@endsection


