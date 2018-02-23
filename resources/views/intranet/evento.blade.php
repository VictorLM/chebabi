@extends('intranet.templates.template')

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
            @endif

            <table class="table table-bordered">
                <tr>
                    <td><b>Título: </b></td>
                    <td>@if(isset($evento)) {{$evento->title}} @endif</td>
                </tr>
                <tr>
                    <td><b>Início: </b></td>
                    <td>@if(isset($evento)) {{Carbon\Carbon::parse($evento->start)->format('d/m/Y H:i')}} @endif</td>
                </tr>
                <tr>
                    <td><b>Término: </b></td>
                    <td>@if(isset($evento)) {{Carbon\Carbon::parse($evento->end)->format('d/m/Y H:i')}} @endif</td>
                </tr>
                <tr>
                    <td><b>Criado por: </b></td>
                    <td>@if(isset($evento)) <a href="mailto:{{$evento->organizador_email}}">{{$evento->organizador_nome}}</a> @endif</td>
                </tr>
                <tr>
                    <td><b>Envolvidos: </b></td>
                    <td>
                        @if(isset($evento)) 
                            <a href="mailto:{{$evento->envolvido1_email}}">{{$evento->envolvido1_nome}}</a>
                            @if (!empty($evento->envolvido2_nome)); @endif
                            <a href="mailto:{{$evento->envolvido2_email}}">{{$evento->envolvido2_nome}}</a>
                            @if (!empty($evento->envolvido3_nome)); @endif
                            <a href="mailto:{{$evento->envolvido3_email}}">{{$evento->envolvido3_nome}}</a>
                            @if (!empty($evento->envolvido4_nome)); @endif
                            <a href="mailto:{{$evento->envolvido4_email}}">{{$evento->envolvido4_nome}}</a>
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
            
        </div>

    </div>
  
</div>

@endsection


