@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="display-inline"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Clientes</h2>
        <input type="text" class="form-control" id="pesquisa-mark-js" placeholder="Pesquisar" autofocus>
    </div>

    <div class="panel-body">
        <div class="row">

            @foreach($clientes as $cliente)
                <div class="col-sm-12 col-md-4 col-lg-3 col-xl-3 cliente">
                    <a href="#" data-toggle="modal" data-target="#myModal-{{$cliente->id}}">
                        <div class="clientes-well well well-lg">
                            <img class="img-responsive cliente-logo" src="@if(!empty($cliente->logo)) {{url($cliente->logo)}} @else {{url('assets/imagens/logo-clientes/logo-cliente-padrao.png')}} @endif" alt="Logo cliente">
                            <p><b>{{$cliente->nome}}</b></p>
                        </div>
                    </a>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="myModal-{{$cliente->id}}" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="fechar close" data-dismiss="modal">&times;</button>
                                <img class="cliente-logo-mini" src="@if(!empty($cliente->logo)) {{url($cliente->logo)}} @else {{url('assets/imagens/logo-clientes/logo-cliente-padrao.png')}} @endif" alt="Logo cliente">
                                <h1 class="modal-title text-center"><b>{{$cliente->nome}}</b></h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <h3 class="text-center tipo-adv-modal-cliente text-jusitfy"><b>Adv(s) Cível(is)</b></h3>
                                        <hr class="hr-advs-clientes">
                                        @for ($i=1; $i<=3; $i++)
                                            @if(!empty($cliente->{"advogado_civel_".$i}) && $cliente->{"advogado_civel_".$i}->ativo)
                                                <ul class="li-adv-cliente">
                                                    <li><b>{{$cliente->{"advogado_civel_".$i}->name}}</b></li>
                                                    <li><i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$cliente->{"advogado_civel_".$i}->email}}">{{$cliente->{"advogado_civel_".$i}->email}}</a></li>
                                                    <li><i class="glyphicon glyphicon-phone-alt"></i> <b>Ramal</b> {{$cliente->{"advogado_civel_".$i}->ramal}}</li>
                                                    <li><i class="glyphicon glyphicon-phone"></i> {{$cliente->{"advogado_civel_".$i}->telefone}}</li>
                                                </ul>
                                                <hr class="hr-advs-clientes">
                                            @endif
                                        @endfor
                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <h3 class="text-center tipo-adv-modal-cliente text-jusitfy"><b>Adv(s) Trabalhista(s)</b></h3>
                                        <hr class="hr-advs-clientes">
                                        @for ($i=1; $i<=3; $i++)
                                            @if(!empty($cliente->{"advogado_trab_".$i}) && $cliente->{"advogado_trab_".$i}->ativo)
                                                <ul class="li-adv-cliente">
                                                    <li><b>{{$cliente->{"advogado_trab_".$i}->name}}</b></li>
                                                    <li><i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$cliente->{"advogado_trab_".$i}->email}}">{{$cliente->{"advogado_trab_".$i}->email}}</a></li>
                                                    <li><i class="glyphicon glyphicon-phone-alt"></i> <b>Ramal</b> {{$cliente->{"advogado_trab_".$i}->ramal}}</li>
                                                    <li><i class="glyphicon glyphicon-phone"></i> {{$cliente->{"advogado_trab_".$i}->telefone}}</li>
                                                </ul>
                                                <hr class="hr-advs-clientes">
                                            @endif
                                        @endfor
                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                        <h3 class="text-center tipo-adv-modal-cliente text-jusitfy"><b>Empresas do Grupo</b></h3>
                                        <hr class="hr-advs-clientes">
                                        <ul class="li-empresas-cliente">
                                            @if(!empty($cliente->empresas))
                                                @foreach(json_decode($cliente->empresas) as $empresa)
                                                    @if(!empty($empresa) && $empresa != " ")
                                                        <li>{{$empresa}}</li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="fechar btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

</div>

@push ('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
    <script src="{{asset('assets/js/mark-js-pesquisa-clientes.js')}}"></script>
    <script>
        //EQUAL HEIGHT COLUMNS BS3
        $(document).ready(function() {
            var heights = $(".well").map(function() {
                return $(this).height();
            }).get(),
            maxHeight = Math.max.apply(null, heights);
            $(".well").height(maxHeight);
        });
    </script>
@endpush

@endsection


