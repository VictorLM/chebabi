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

            <div class="panel-body inline">

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
                    <div style="margin:1em;">
                        <span>Eu, <b>{{Auth::user()->name}},</b> atesto que &nbsp;</span>
                        <select class="novo-uau-input" style="width:auto;height:auto;" name="para" id="para" required @if($errors->has('para')) style="border-color:red;" autofocus @endif>
                            <option></option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}" @if(old('para')==$user->id) selected @endif>{{$user->name}}</option>
                            @endforeach
                        </select>
                        <span>arrasa no dia a dia!</span>
                        <br/><br/>
                        <span>Participa, muda, inova, surpreende, marca e causa <b>UAU! </b> Porque:</span>
                        <br/><br/>
                        <textarea class="novo-uau-input" name="motivo" id="motivo" maxlength="400" 
                        @if($errors->has('motivo')) style="border-color:red;" @endif>{{old('motivo')}}</textarea>
                        <br/>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <img class="novo-uau-logo-pr" src="{{url('assets/imagens/logopr.png')}}" alt="Logo Ponto de Referência">
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <img class="novo-uau-logo-ic" src="{{url('assets/imagens/logo_md_20_anos.png')}}" alt="Logo Izique Chebabi">
                            </div>
                        </div>
                        <br/>
                        <button type="submit" class="btn btn-primary btn-lg" style="float:right;"><i class="glyphicon glyphicon-send"></i> Enviar</button>
                    </div>
                    
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


