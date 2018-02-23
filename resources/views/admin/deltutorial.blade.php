@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

        <div class="panel-heading">
            <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Excluir Tutorial</h2>
        </div>

        <div class="panel-body">
            
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                    <th>Nome</th>
                    <th>Incluido em</th>
                    <th>Excluir</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tutoriais as $tutorial)
                    <tr>
                        <td>{{$tutorial->name}}</td>
                        <td>{{Carbon\Carbon::parse($tutorial->created_at)->format('d/m/y')}}</td>
                        <td>
                            <form method="POST" action="{{action('Admin\AdminController@del_tutorial')}}">
                                {!! csrf_field() !!}
                                <input type="text" name="id" value="{{$tutorial->id}}" hidden="hidden">
                                <button class="btn btn-danger" type="submit">
                                    <i class="glyphicon glyphicon-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        
            {!! $tutoriais->links() !!}

        </div>

    </div>
  
</div>

@endsection


