@extends('intranet.templates.template')

@section('content')

<div class="container">

    <div class="panel panel-default">

      <div class="panel-heading">
        <h2>
          <form class="form-inline" method="POST" id="form" action="{{action('Intranet\IntranetController@aniversariantes_filtrados')}}">
              {{ csrf_field() }}
                <label style="font-weight: normal;"><a href="{{url('/intranet')}}"><i class="glyphicon glyphicon-arrow-left"></i></a> Aniversariantes do mês de </label>
                  <select name="mes" class="form-control " form="form" required="required">
                    <option value="1" @if($mes == 1) selected @endif>Janeiro</option>
                    <option value="2" @if($mes == 2) selected @endif>Fevereiro</option>
                    <option value="3" @if($mes == 3) selected @endif>Março</option>
                    <option value="4" @if($mes == 4) selected @endif>Abril</option>
                    <option value="5" @if($mes == 5) selected @endif>Maio</option>
                    <option value="6" @if($mes == 6) selected @endif>Junho</option>
                    <option value="7" @if($mes == 7) selected @endif>Julho</option>
                    <option value="8" @if($mes == 8) selected @endif>Agosto</option>
                    <option value="9" @if($mes == 9) selected @endif>Setembro</option>
                    <option value="10" @if($mes == 10) selected @endif>Outubro</option>
                    <option value="11" @if($mes == 11) selected @endif>Novembro</option>
                    <option value="12" @if($mes == 12) selected @endif>Dezembro</option>
                  </select>
  
                  <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
                  <a href="{{url('/intranet/aniversariantes')}}">
                    <button type="button" class="btn btn-danger"><i class="glyphicon glyphicon-erase"></i> Limpar</button>
                  </a>
          </form>
        </h2>

      </div>

      <div class="panel-body">
          <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Aniversário</th>
                </tr>
              </thead>
              <tbody>

                @if(isset($users) && count($users)>0)

                  @foreach($users as $user)
                    <tr @if(Carbon\Carbon::parse($user->nascimento)->format('d/m') 
                      == Carbon\Carbon::parse(Carbon\Carbon::today())->format('d/m')) style="font-weight:bold" @endif>
                      <td>{{$user->name}}</td>
                      <td>
                        {{Carbon\Carbon::parse($user->nascimento)->format('d/m')}} 
                        @if(Carbon\Carbon::parse($user->nascimento)->format('d/m') 
                        == Carbon\Carbon::parse(Carbon\Carbon::today())->format('d/m')) 
                        <i class="glyphicon glyphicon-gift"></i> Hoje!
                        @endif
                      </td>
                    </tr>
                  @endforeach

                @else
                    <small>* Nenhum aniversariante encontrado nesse mês.</small>
                @endif
                
              
              </tbody>
            </table>
          
            @if(isset($users) && count($users)>0)
              {!! $users->appends(Request::only(['mes'=>'mes']))->links() !!}
            @endif
      </div>

    </div>

</div>

@endsection


