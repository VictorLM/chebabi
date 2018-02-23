@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Tarifadores</h2>
      </div>

      <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Código telefone</th>
                <th>Código impressão</th>
              </tr>
            </thead>
            <tbody>
            @foreach($tarifadores as $tarifador)
                <tr>
                    <td>{{$tarifador->cliente}}</td>
                    <td>{{$tarifador->tel}}</td>
                    <td>{{$tarifador->imp}}</td>
                </tr>
            @endforeach
            </tbody>
          </table>
    
          {!! $tarifadores->links() !!}
      </div>

    </div>

</div>

@endsection


