@extends('intranet.templates.template')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@push('meta')
    <meta http-equiv="refresh" content="60"/>
@endpush

@section('content')

<div class="container-index">
    {{-- Alertas flash-messages --}}
    @if (Session::has('alert-success'))
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <li>{{Session::get('alert-success')}}</li>
        </div>
    @elseif ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <li>{{$error}}</li>
            </div>
        @endforeach
    @endif

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2>
                <a href="{{url('/intranet/terapias')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                Agendamento <b>Quick Massage</b>
            </h2>
            <small>
                São {{$terapia['tempo_sessao']}} minutos por sessão. Saiba mais sobre Quick Massage <a href="https://pt.wikipedia.org/wiki/Anma" target="_blank">aqui</a>. Sessões todos os dias, de segunda a sexta-feira.<br/>
                <b>O limite de agendamentos por usuário é de 1 sessão por semana e até {{$terapia['limite_mensal']}} por mês.</b>
            </small>
        </div>

        <div class="panel-body">

            <div class="row">
                @foreach(array_chunk($quick_massage_array['dias'], 2) as $chunk_dias)

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">

                        @foreach($chunk_dias as $key => $dia)

                            <div class="box">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="text-center" style="margin:0;"><b>{{$dia['dia_feira']}} - {{Carbon\Carbon::parse($dia['dia'])->format('d/m/Y')}}</b></h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">

                                            @include('intranet.terapias.foreach_table_body')

                                        </table>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    </div>

                @endforeach

            </div>

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
    <script src="{{asset('assets/js/agendamento_terapias.js')}}"></script>
@endpush

@endsection


