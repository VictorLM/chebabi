@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <h2 style="margin-top:0px;">
                        <a href="{{url('/intranet/uau')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> 
                        Uaus Enviados
                    </h2>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <a href="{{url('/intranet/meus-uaus')}}">
                    <button type="button" class="uaubtn btn btn-sm btn-danger">
                        <i class="glyphicon glyphicon-heart"></i> Uaus <br/> Recebidos
                    </button></a>
                    <a href="{{url('/intranet/novo-uau')}}">
                    <button type="button" class="btn btn-sm btn-primary">
                        <i class="glyphicon glyphicon-send"></i> Enviar <br/> Uau!
                    </button></a>
                </div>
            </div>

        </div>

        <div class="panel-body">

            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Para</th>
                        <th>Motivo</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uaus as $uau)
                        <tr>
                            <td>{{Carbon\Carbon::parse($uau->created_at)->format('d/m/y')}}</td>
                            <td>{{$uau->para_nome->name}}</td>
                            <td>{{$uau->motivo}}</td>
                            <td>
                                <a href="{{action('Intranet\IntranetController@editar_uau', $uau->id)}}">
                                    <button class="btn btn-primary" type="button">
                                        <i class="glyphicon glyphicon-pencil"></i> Editar
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    
            {!! $uaus->links() !!}


        </div>

    </div>

@endsection