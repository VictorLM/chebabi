@extends('intranet.templates.template')

@section('content')

<div class="container">
  <h2><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Tutoriais</h2>          
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Tutorial</th>
        <th>Incluido em</th>
        <th>Link para visualização</th>
      </tr>
    </thead>
    <tbody>
    @foreach($tutoriais as $tutorial)
        <tr>
            <td>{{$tutorial->name}}</td>
            <td>{{Carbon\Carbon::parse($tutorial->created_at)->format('d/m/y')}}</td>
            <td><a href="{{$tutorial->link}}" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> Visualizar</a></td>
        </tr>
    @endforeach
    </tbody>
  </table>

  {!! $tutoriais->links() !!}
  
</div>

@endsection


