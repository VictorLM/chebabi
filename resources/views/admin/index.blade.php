@extends('intranet.templates.template')

@section('content')

<div class="container">
    
        <div class="col-md12">
            
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                    <div class="alert alert-success alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ Session::get('alert-' . $msg) }}
                    </div>
                @endif
            @endforeach
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Painel do Administrador
                </div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">USUÁRIOS</div>
                            <div class="panel-body">

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/register')}}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <i class="glyphicon glyphicon-user"></i>
                                        <i class="glyphicon glyphicon-paperclip"></i>
                                        <br/>CADASTRAR</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/users')}}">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                        <i class="glyphicon glyphicon-user"></i>
                                        <i class="glyphicon glyphicon-paperclip"></i>
                                        <br/>EDITAR</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">ADVOGADOS</div>
                            <div class="panel-body">
                                
                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/advs/create')}}">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <i class="glyphicon glyphicon-user"></i>
                                            <i class="glyphicon glyphicon-briefcase"></i>
                                            <br/>CADASTRAR</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/advs')}}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                            <i class="glyphicon glyphicon-user"></i>
                                            <i class="glyphicon glyphicon-briefcase"></i>
                                            <br/>EDITAR</a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
                

                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">PROCEDIMENTOS</div>
                            <div class="panel-body">
                                
                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/novo_procedimento')}}">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <i class="glyphicon glyphicon-ok"></i>
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            <br/>CADASTRAR</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/del_procedimento')}}">
                                            <i class="glyphicon glyphicon-trash"></i>
                                            <i class="glyphicon glyphicon-ok"></i>
                                            <i class="glyphicon glyphicon-list-alt"></i>
                                            <br/>EXCLUIR</a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">TARIFADORES</div>
                            <div class="panel-body">
                                
                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/novo_tarifador')}}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <i class="glyphicon glyphicon-earphone"></i>
                                        <i class="glyphicon glyphicon-print"></i>
                                        <br/>CADASTRAR</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/del_tarifador')}}">
                                        <i class="glyphicon glyphicon-trash"></i>
                                        <i class="glyphicon glyphicon-earphone"></i>
                                        <i class="glyphicon glyphicon-print"></i>
                                        <br/>EXCLUIR</a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                </div>
                            

                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">TUTORIAIS</div>
                            <div class="panel-body">
                                
                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/novo_tutorial')}}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <i class="glyphicon glyphicon-question-sign"></i>
                                        <i class="glyphicon glyphicon-book"></i>
                                        <br/>CADASTRAR</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/del_tutorial')}}">
                                        <i class="glyphicon glyphicon-trash"></i>
                                        <i class="glyphicon glyphicon-question-sign"></i>
                                        <i class="glyphicon glyphicon-book"></i>
                                        <br/>EXCLUIR</a>
                                    </div>
                                </div>
                    
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">OUTROS</div>
                            <div class="panel-body">
                                
                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet/admin/relatorios')}}">
                                        <i class="glyphicon glyphicon-list"></i>
                                        <i class="glyphicon glyphicon-file"></i>
                                        <i class="glyphicon glyphicon-road"></i><br/>RELATÓRIOS</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="intra-atalhos well well-lg">
                                        <a href="{{url('intranet')}}">
                                        <i class="glyphicon glyphicon-arrow-left"></i>
                                        <i class="glyphicon glyphicon-globe"></i>
                                        <i class="glyphicon glyphicon-cloud"></i><br/>INTRANET</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                </div>
                    
                </div>
 
            </div>
        </div>
    </div>
</div>

@endsection


