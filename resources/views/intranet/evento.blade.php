@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet/agenda')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                @if(isset($evento)) 
                    @if($evento->cancelado) 
                        <span class="label label-danger">CANCELADO</span> <strike>{{$evento->title}}</strike>
                    @else
                        {{$evento->title}}
                    @endif 
                @endif
            </h2>
        </div>

        <div class="panel-body">

            @if (Session::has('alert-success'))
                <div class="alert alert-success alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <li>{{ Session::get('alert-success') }}</li>
                </div>
            @elseif ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-bordered">
                <tr>
                    <td><b>Título: </b></td>
                    <td>@if(isset($evento)) {{$evento->title}} @endif</td>
                </tr>
                <tr>
                    <td><b>Início: </b></td>
                    <td>
                        @if(!empty(unserialize($evento->dow)))
                            Toda  
                            @foreach(unserialize($evento->dow) as $dia)
                                @if($dia == 1)
                                    Segunda-feira;
                                @elseif($dia == 2)
                                    Terça-feira;
                                @elseif($dia == 3)
                                    Quarta-feira;
                                @elseif($dia == 4)
                                    Quinta-feira;
                                @elseif($dia == 5)
                                    Sexta-feira;
                                @endif
                            @endforeach
                            às 
                            @if(isset($evento)) {{Carbon\Carbon::parse($evento->start)->format('H:i')}} @endif
                            <small><b><i> * Evento recorrente </b></i></small>
                        @else
                            @if(isset($evento)) {{Carbon\Carbon::parse($evento->start)->format('d/m/Y H:i')}} @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><b>Término: </b></td>
                    <td>
                        @if(!empty(unserialize($evento->dow)))
                            Toda  
                            @foreach(unserialize($evento->dow) as $dia)
                                @if($dia == 1)
                                    Segunda-feira;
                                @elseif($dia == 2)
                                    Terça-feira;
                                @elseif($dia == 3)
                                    Quarta-feira;
                                @elseif($dia == 4)
                                    Quinta-feira;
                                @elseif($dia == 5)
                                    Sexta-feira;
                                @endif
                            @endforeach
                            às 
                            @if(isset($evento)) {{Carbon\Carbon::parse($evento->end)->format('H:i')}} @endif
                        @else
                            @if(isset($evento)) {{Carbon\Carbon::parse($evento->end)->format('d/m/Y H:i')}} @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><b>Criado por: </b></td>
                    <td>@if(isset($evento)) <a href="mailto:{{$evento->organizador_email}}">{{$evento->organizador_nome}}</a> @endif</td>
                </tr>
                <tr>
                    <td><b>Envolvidos: </b></td>
                    <td>
                        @if(isset($evento))
                            @foreach(unserialize($evento->envolvidos) as $envolvido)
                                <a href="mailto:{{$envolvido['emailAddress']['address']}}">{{$envolvido['emailAddress']['name']}}</a>; 
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><b>Local: </b></td>
                    <td>@if(isset($evento)) {{$evento->local}} @endif</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>Descrição: </b><br/>
                        @if(isset($evento)) {!!$evento->descricao!!} @endif
                    </td>
                </tr>
        
            </table>

            @if(!empty($evento->alterado))
                <span>{{$evento->alterado}}</span><br/>
            @endif

            @if(empty(unserialize($evento->dow)) && ($evento->end < Carbon\Carbon::now() || $evento->cancelado))
                <span>* Evento finalizado.</span>
            @endif

            @if($edit_cancel && !$evento->cancelado)
                @if(!empty(unserialize($evento->dow)) || $evento->end > Carbon\Carbon::now())
                    <br/>
                    <a href="{{action('Intranet\IntranetController@editEvento', $evento->id)}}">
                        <button class="btn btn-primary" type="button">
                            <i class="glyphicon glyphicon-pencil"></i> Editar
                        </button>
                    </a>
                    <a href="{{action('APIs\MicrosoftController@cancela_evento', $evento->id)}}">
                        <button class="btn btn-danger cancelar-btn" type="button">
                            <i class="glyphicon glyphicon-trash"></i> Cancelar
                        </button>
                    </a>
                @endif
            @endif

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h2>Aguarde...</h2>
                    <i class="fa fa-cog fa-spin fa-3x fa-fw" style="font-size: 10em;"></i>
                </div>
            </div>
        </div>
    </div>
  
</div>

@push ('scripts')
    <script src="{{asset('assets/js/evento.js')}}"></script>
@endpush

@endsection


