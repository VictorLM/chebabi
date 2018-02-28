@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

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

                <form method="POST" id="form" action="{{action('Intranet\IntranetController@enviar_uau')}}">
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

            <!-- Modal -->
            <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content text-center">
                        <div class="modal-body">
                            <h2>Enviando...<br/>Aguarde.</h2>
                            <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

@push ('scripts')
    <script src="{{asset('assets/js/modal_loader.js')}}"></script>
@endpush

@endsection


