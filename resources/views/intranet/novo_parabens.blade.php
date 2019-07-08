@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2>
                    <a href="{{url('/intranet/aniversariantes')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Parabenizar - {{$user->name}}
                </h2>
            </div>

            <div class="panel-body bday-form-div">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10" style="margin:1em;">
                        <form method="POST" id="form" action="{{action('Intranet\IntranetController@parabens_enviar')}}">
                            {!! csrf_field() !!}
                            <span>Parabenize <b>{{$user->name}}</b> pelo seu aniversário:</span>
                            <br/><br/>
                            <input type="hidden" name="para" value="{{$user->id}}">
                            <textarea class="novo-uau-input" name="mensagem" id="mensagem" maxlength="1000" 
                            @if($errors->has('mensagem')) style="border-color:red;" @endif>{{old('mensagem')}}</textarea>
                            <small>* Somente o usuário destinatário terá acesso a essa mensagem.</small>
                            <br/><br/>
                            <button type="submit" class="btn btn-primary btn-lg" style="float:right;"><i class="glyphicon glyphicon-send"></i> Enviar</button>
                            <br/><br/>
                        </form>
                    </div>
                </div>

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


