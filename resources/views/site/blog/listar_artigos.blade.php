@extends('intranet.templates.template')

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Blog - Editar Artigos</h2>
            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Data inclusão</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-justify">
                        @if(count($artigos)>0)
                            @foreach($artigos as $artigo)
                                <tr>
                                    <td><a target="_blank" href="{{$artigo->url}}">{{$artigo->titulo}}</a></td>
                                    <td>{{$artigo->autor}}</td>
                                    <td>{{$artigo->categoria}}</td>
                                    <td>{{strip_tags(str_limit($artigo->descricao, 400, ' [...]'))}}</td>
                                    <td>{{Carbon\Carbon::parse($artigo->created_at)->format('d/m/Y H:i')}}</td>
                                    <td>
                                        <a href="{{action('Blog\BlogController@editar_artigo', $artigo->id)}}">
                                            <button class="btn btn-primary" type="button" title="Editar">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </button>
                                        </a>
                                        <p class="card-text card-text-home"></p>
                                        <form method="POST" action="{{action('Blog\BlogController@excluir_artigo')}}">
                                            {{ csrf_field() }}
                                            <input name="id" type="hidden" value="{{$artigo->id}}">
                                            <button class="btn btn-danger" type="submit" title="Excluir">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                        </form>
                                        <p class="card-text card-text-home"></p>
                                        <a href="{{url("/intranet/admin/comentarios-artigo/$artigo->id")}}">
                                            <button class="btn btn-success" type="button" title="Comentários">
                                                <i class="glyphicon glyphicon-comment"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <h3 class="text-center">Nenhum artigo encontrado.</h3>
                        @endif
                    </tbody>
                </table>
            
                {!! $artigos->links() !!}

            </div>

        </div>

</div>

@endsection


