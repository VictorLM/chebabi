@extends('layouts.app')

@push('styles')
    <link href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container"> 
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><a href="{{url('/intranet/agendamento-massagem')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Incluir dia sem Massagem</h2>
                    <small>
                        As datas cadastradas aqui serão bloqueadas para não haver agendamento de sessões de massagem.<br/>
                        <b>Somente os Administradores e a Recepção tem acesso a esta tela.</b>
                    </small>
                </div>

                <div class="panel-body">
                    
                    @if (Session::has('alert-success'))
                        <div class="alert alert-success alert-dismissable">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <li>{{Session::get('alert-success')}}</li>
                        </div>
                    @elseif ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form class="form-horizontal" id="form" method="POST" action="{{action('Intranet\IntranetController@incluir_dia_sem_massagem')}}">
                        {{csrf_field()}}
                        <div class="form-group{{ $errors->has('data') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">* Data</label>
                            <div class="col-md-6">
                                <input id="data" type="date" class="form-control" name="data" value="{{old('data')}}" required autofocus>
                                @if ($errors->has('data'))
                                    <span class="help-block">
                                        <strong>{{$errors->first('data')}}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Incluir</button>
                            </div>
                        </div>
                        <span>* Campos obrigatórios.</span><br/>

                    </form>

                    <table class="table table-bordered">
                        <h3 class="text-center">Datas cadastradas</h3>
                        <thead>
                            <tr>
                                <th class="text-center">Data</th>
                                <th class="text-center">Data inclusão</th>
                            </tr>
                        </thead>
                            @foreach($dias_sem_massagem as $dia_sem_massagem)
                                <tr class="text-center">
                                    <td>{{Carbon\Carbon::parse($dia_sem_massagem->data)->format('d/m/Y')}}</td>
                                    <td>{{Carbon\Carbon::parse($dia_sem_massagem->created_at)->format('d/m/Y H:i')}}</td>
                                </tr>
                            @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h2>Cadastrando...<br/>Aguarde.</h2>
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
