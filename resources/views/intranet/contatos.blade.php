@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="display-inline"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Contatos</h2>
        <input type="text" class="form-control" id="pesquisa-mark-js" placeholder="Pesquisar">
      </div>

      <div class="panel-body">
          <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Ramal</th>
                  <th>Telefone</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody id="tbody">
              @foreach($users as $user)
                  <tr>
                      <td>{{$user->name}}</td>
                      <td>{{$user->ramal}}</td>
                      <td>{{$user->telefone}}</td>
                      <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
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


