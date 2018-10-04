@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        <h2 class="display-inline"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Tutoriais</h2>
        <input type="text" class="form-control" id="pesquisa-mark-js" placeholder="Pesquisar">
      </div>

      <div class="panel-body">
        <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Tutorial</th>
                <th>Incluido em</th>
                <th>Link para visualização</th>
              </tr>
            </thead>
            <tbody id="tbody">
            @foreach($tutoriais as $tutorial)
                <tr>
                    <td>{{$tutorial->name}}</td>
                    <td>{{Carbon\Carbon::parse($tutorial->created_at)->format('d/m/y')}}</td>
                    <td><a href="{{$tutorial->link}}" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> Visualizar</a></td>
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


