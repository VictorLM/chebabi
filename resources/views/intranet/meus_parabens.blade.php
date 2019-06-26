@extends('intranet.templates.template')
@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
@endpush
@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h2 style="margin-top:0px;">
                        <a href="{{url('/intranet/aniversariantes')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                        Felicitações Recebidas
                    </h2>
                </div>
            </div>

        </div>

        <div class="panel-body">
            
            @if(!$meus_parabens->isEmpty())
                <table class="meusuaus table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>De</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meus_parabens as $parabens)
                        <tr @if(!$parabens->lido) style="font-weight:bold" @endif>
                            <td>
                                <a href="" class="uaulink @if(!$parabens->lido)parabens-nao-lido @endif" data-toggle="modal" data-target="#myModal-{{$parabens->id}}">
                                    {{$parabens->de_user->name}}
                                    <span style="display:none">{{$parabens->id}}</span>
                                </a>
                            </td>
                            <td>
                                <a href="" class="uaulink @if(!$parabens->lido)parabens-nao-lido @endif" data-toggle="modal" data-target="#myModal-{{$parabens->id}}">
                                    {{Carbon\Carbon::parse($parabens->created_at)->format('d/m/y')}}
                                    <span style="display:none">{{$parabens->id}}</span>
                                </a>
                            </td>
                        </tr>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="myModal-{{$parabens->id}}" role="dialog">
                            <div class="modal-dialog">
        
                                <!-- Modal content-->
                                <div class="modal-meus-parabens modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="fechar close" data-dismiss="modal">&times;</button>
                                        <h1 class="modal-title text-center uau-title">Parabéns!</h1>
                                    </div>
                                    <div class="uau modal-body">
                                        <p>{{$parabens->mensagem}}</p><br/>
                                        <p class="text-right"><i>{{$parabens->de_user->name}}</i></p>
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
                {!! $meus_parabens->links() !!}
        
            @else
                <h3 class="text-center">Você ainda não recebeu nenhum parabéns. =(</h3>
            @endif

        </div>

    </div>

@push ('scripts')
    <script src="{{ asset('assets/js/parabens.js') }}"></script>
@endpush

@endsection


