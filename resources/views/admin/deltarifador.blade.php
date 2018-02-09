@extends('intranet.templates.template')

@section('content')

<div class="container">
  <h2><a href="{{url('/intranet/admin')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Excluir Tarifador</h2>

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Código telefone</th>
            <th>Código impressão</th>
            <th>Excluir</th>
          </tr>
        </thead>
        <tbody>
        @foreach($tarifadores as $tarifador)
            <tr>
                <td>{{$tarifador->cliente}}</td>
                <td>{{$tarifador->tel}}</td>
                <td>{{$tarifador->imp}}</td>
                <td>
                    <form method="POST" action="{{action('Admin\AdminController@del_tarifador')}}">
                        {!! csrf_field() !!}
                        <input type="text" name="id" value="{{$tarifador->id}}" hidden="hidden">
                        <button class="btn btn-danger" type="submit">
                            <i class="glyphicon glyphicon-trash"></i> Excluir
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
      </table>

      {!! $tarifadores->links() !!}

</div>

@endsection


