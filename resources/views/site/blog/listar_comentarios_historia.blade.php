@extends('intranet.templates.template')

@section('content')

<div class="container">

        <div class="panel panel-default">

            <div class="panel-heading">
                <h2><a href="{{url('/intranet/admin/blog-editar-historia')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Blog - Comentários da História</h2>
            </div>

            <div class="panel-body">
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Autor</th>
                            <th>E-mail</th>
                            <th>Data</th>
                            <th>Comentário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-justify">
                        @if(count($comentarios)>0)
                            @foreach($comentarios as $comentario)
                                <tr>
                                    <td>{{$comentario->autor}}</td>
                                    <td>{{$comentario->email}}</td>
                                    <td>{{Carbon\Carbon::parse($comentario->created_at)->format('d/m/Y H:i')}}</td>
                                    <td>{{$comentario->comentario}}</td>
                                    
                                    <td>
                                        <form method="POST" action="{{action('Blog\BlogController@excluir_comentario_historia')}}">
                                            {{ csrf_field() }}
                                            <input name="id" type="hidden" value="{{$comentario->id}}">
                                            <button class="btn btn-danger" type="submit" title="Excluir">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <h3 class="text-center">Nenhum comentário encontrado para este artigo.</h3>
                        @endif

                    </tbody>
                </table>
            
                {!! $comentarios->links() !!}

            </div>

        </div>

</div>

@endsection


