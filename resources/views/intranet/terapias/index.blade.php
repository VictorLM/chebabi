@extends('intranet.templates.template')
@push('styles')
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
@endpush
@section('content')

<div class="container">

    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <div class="alert alert-success alert-dismissable" style="margin-left:10px;margin-right:10px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ Session::get('alert-' . $msg) }}
            </div>
        @endif
    @endforeach

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 cols-index">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Agendamento Terapias</h2>
                <small>
                    <b>Cada colaborador poderá agendar uma sessão de terapia por dia, exceto MAT Pilates.</b><br/>
                    Caso hajam sessões livres 30 minutos antes do início das mesmas, será liberado o agendamento para os colaboradores que já tenham atingido os limites de agendamentos.
                </small>
            </div>

            <div class="panel-body">

                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="terapias-link intra-atalhos well well-lg">
                            <h1><a href="{{url('intranet/terapias/quick-massage')}}">QUICK<br/>MASSAGE</a></h1>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="terapias-link intra-atalhos well well-lg">
                            <h1><a href="{{url('intranet/terapias/auriculoterapia')}}">AURICULO<br/>TERAPIA</a></h1>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="terapias-link intra-atalhos well well-lg">
                            <h1><a href="{{url('intranet/terapias/massagem-pes')}}">MASSAGEM<br/>NOS PÉS</a></h1>
                        </div>
                    </div>

                </div>
                {{--
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="terapias-link intra-atalhos well well-lg">
                            <h1><a href="{{url('intranet/terapias/quick-relaxante')}}">MASSAGEM<br/>RELAXANTE</a></h1>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="terapias-link intra-atalhos well well-lg">
                            <h1><a href="{{url('intranet/terapias/mat-pilates')}}">MAT<br/>PILATES</a></h1>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="terapias-link intra-atalhos well well-lg">
                            <h1><a href="{{url('intranet/terapias/minhas-sessoes')}}">MINHAS<br/>SESSÕES</a></h1>
                        </div>
                    </div>

                </div>
                
                <div class="row">

                    @if(Auth::user()->is_admin_recepcao())
                        <div class="col-md-4">
                            <div class="terapias-link intra-atalhos well well-lg">
                                Gráficos? Lista agendamentos?
                                <span class="glyphicon glyphicon-bookmark float-right" style="color:#337ab7;"></span>
                                <h1><a href="{{url('intranet/terapias/painel-admin')}}">DIAS SEM<br/>TERAPIAS</a></h1>
                            </div>
                        </div>
                    @endif

                </div>
                --}}
                
            </div>
        </div>
    </div>


</div>

@endsection