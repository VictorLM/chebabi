@extends('intranet.templates.template')

@section('content')

<div class="container">
    
    <div class="panel panel-default">

        <div class="panel-heading">
            <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Enviar Sugestão</h2>
            <small>Obrigado por colaborar. Sua sugestão será analisada e se aprovada, será implementada.</small><br/>
            <small>* Não se preocupe, sua sugestão será enviada anônimamente.</small>
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

            <form method="POST" action="{{action('Intranet\IntranetController@enviar_sugestao')}}">
                {!! csrf_field() !!}
                <div class="form-group">
                <label for="sugestao">Sugestão</label>
                <textarea class="form-control" name="sugestao" maxlength="2000" 
                @if($errors->has('sugestao')) style="border-color:red;" autofocus @endif>{{old('sugestao')}}</textarea>
                    @if ($errors->has('sugestao'))
                        <small style="color:red;">
                            {{ $errors->first('sugestao') }}
                        </small>
                        <br/>
                    @else
                        <small>* Máximo 2000 caracteres.</small>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-send"></i> Enviar</button>
            </form>
            
        </div>

    </div>

@endsection


