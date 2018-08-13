<?php

$evento_atualizado = array (
  'subject' => '['.$request->tipo.'] '.$request->titulo,
  'body' => 
  array (
    'contentType' => 'HTML',
    'content' => $request->descricao,
  ),
  'start' => 
  array (
    'dateTime' => $request->iniciodata.'T'.$request->iniciohora.':00',
    'timeZone' => 'E. South America Standard Time',
  ),
  'end' => 
  array (
    'dateTime' => $request->terminodata.'T'.$request->terminohora.':00',
    'timeZone' => 'E. South America Standard Time',
  ),
  'location' => 
  array (
    'displayName' => $request->local,
  ),
  'attendees' => 
  array (

    0 => 
      array (
      'emailAddress' => 
      array (
        'address' => $evento->organizador_email,
        'name' => $evento->organizador_nome,
      ),
      'type' => 'required',
    ),

  ),
);

if(count($request->envolvidos)>0){

  $i = count($request->envolvidos);
  $contador = 0;

  do{
    
    $evento_atualizado['attendees'][] =
          
    array (
        'emailAddress' => 
        array (
            'address' => $user[$contador]->email,
            'name' => $user[$contador]->name
        ),
        'type' => 'required',
    );

    $i--;
    $contador++;

  }while($i < count($request->envolvidos) && $i > 0);

}

if(!empty($request->recorrencia)){
  $recurrence = array (
    "recurrence" =>
    array (
      "pattern" =>
      array (
        "type" => "weekly",
        "interval" => 1,
        "daysOfWeek" => 
        array ()
      ),
      "range" =>
      array (
        "startDate" => $request->iniciodata,
        "type" => "noEnd"
      )
    )
  );
  foreach($request->recorrencia as $dia){
    $recurrence["recurrence"]["pattern"]["daysOfWeek"][] = $dia;
  }
  $evento_atualizado = array_merge($evento_atualizado, $recurrence);
}else{
  $recurrence = array (
    "recurrence" => null
  );
  $evento_atualizado = array_merge($evento_atualizado, $recurrence);
}