@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        <h2 class="display-inline"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Tarifadores</h2>
        <input type="text" class="form-control" id="pesquisa-mark-js" placeholder="Pesquisar" autofocus>
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
            <tbody id="tbody">
            @foreach($tarifadores as $tarifador)
                <tr>
                    <td>{{$tarifador->cliente}}</td>
                    <td>{{$tarifador->tel}}</td>
                    <td>{{$tarifador->imp}}</td>
                </tr>
            @endforeach
            </tbody>
          </table>

      </div>

    </div>

</div>

@push ('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
  <script src="{{asset('assets/js/mark-js-pesquisa.js')}}"></script>
@endpush

@endsection


