@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Contatos</h2> 
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
              <tbody>
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
          
            {!! $users->links() !!}
      </div>

    </div>

</div>

@endsection


