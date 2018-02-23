@extends('intranet.templates.template')
@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
@endpush
@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">

                <h2>
                    <a href="{{url('/intranet/uau')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                    Meus Uaus
                    <a href="{{url('/intranet/novo-uau')}}">
                    <button type="button" class="uaubtn btn btn-md btn-primary">
                        <i class="glyphicon glyphicon-send"></i> Enviar Uau
                    </button></a>
                </h2>

            </div>

            <div class="panel-body">
                
                @if(!$uaus->isEmpty())
                    <table class="meusuaus table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>De</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($uaus as $uau)
                            <tr @if(!$uau->lido) style="font-weight:bold" @endif>
                                <td><a href="" class="uaulink @if(!$uau->lido)uaunaolido @endif" data-toggle="modal" data-target="#myModal-{{$uau->id}}">
                                        {{$uau->de_nome->name}}
                                        <span style="display:none">{{$uau->id}}</span>
                                    </a>
                                </td>
                                <td><a href="" class="uaulink @if(!$uau->lido)uaunaolido @endif" data-toggle="modal" data-target="#myModal-{{$uau->id}}">
                                        {{Carbon\Carbon::parse($uau->created_at)->format('d/m/y')}}
                                        <span style="display:none">{{$uau->id}}</span>
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Modal -->
                            <div class="modal fade" id="myModal-{{$uau->id}}" role="dialog">
                                <div class="modal-dialog">
            
                                    <!-- Modal content-->
                                    <div class="modaluau modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="fechar close" data-dismiss="modal">&times;</button>
                                            <h1 class="modal-title text-center uau-title">UAU!</h1>
                                        </div>
                                    <div class="uau modal-body">
                                        <p>Eu, <b>{{$uau->de_nome->name}},</b> atesto que <b>{{$uau->para_nome->name}}</b> 
                                        arrasa no dia a dia!</p>
                                        <p>Participa, muda, inova, surpreende, marca e causa UAU!</p>
                                        <p>Porque: <b>{{$uau->motivo}}</b></p>
                                        <div class="row">
                                            <img src="{{url('assets/imagens/logopr.png')}}" class="float-left" alt="Logo Ponto de Referência">
                                            <img src="{{url('assets/imagens/logo4.png')}}" class="float-right" alt="Logo Izique Chebabi">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="fechar btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>
                                    </div>
            
                                </div>
                            </div>
                            
                            @endforeach
                        </tbody>
                    </table>
                    {!! $uaus->links() !!}
            
                @else
                    <h3 class="text-center">Você ainda não recebeu nenhum Uau. =(</h3>
                @endif

            </div>

        </div>

@push ('scripts')
    <script src="{{ asset('assets/js/uau.js') }}"></script>
@endpush

@endsection


