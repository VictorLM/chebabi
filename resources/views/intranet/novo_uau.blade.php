@extends('intranet.templates.template')

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2>
                    <a href="{{url('/intranet/uau')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Enviar Uau
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

                <form method="POST" action="{{action('Intranet\IntranetController@enviar_uau')}}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="para">Para</label>
                        <select class="form-control" name="para" id="para" required @if($errors->has('para')) style="border-color:red;" autofocus @endif>
                            <option></option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}" @if(old('para')==$user->id) selected @endif>{{$user->name}}</option>
                        @endforeach
                        </select>
                        @if ($errors->has('para'))
                            <small style="color:red;">
                                {{ $errors->first('para') }}
                            </small>
                            <br/>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo</label>
                        <textarea class="form-control" name="motivo" id="motivo" maxlength="200" 
                        @if($errors->has('motivo')) style="border-color:red;" autofocus @endif>{{old('motivo')}}</textarea>
                        @if ($errors->has('motivo'))
                            <small style="color:red;">
                                {{ $errors->first('motivo') }}
                            </small>
                            <br/>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-send"></i> Enviar</button>
                </form>

            </div>

        </div>

@endsection


