<?php

if(count($user)==1){
  $evento = array (
    'subject' => '['.$request->tipo.'] - ' . $request->titulo,
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
          'address' => $user[0]->email,
          'name' => $user[0]->name,
        ),
        'type' => 'required',
      ),
      1 => 
        array (
        'emailAddress' => 
        array (
          'address' => Auth::user()->email,
          'name' => Auth::user()->name,
        ),
        'type' => 'required',
      ),
    ),
  );

}else if(count($user)==2){

  $evento = array (
    'subject' => '['.$request->tipo.'] - ' . $request->titulo,
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
          'address' => $user[0]->email,
          'name' => $user[0]->name,
        ),
        'type' => 'required',
      ),
       1 => 
        array (
        'emailAddress' => 
        array (
          'address' => $user[1]->email,
          'name' => $user[1]->name,
        ),
        'type' => 'required',
      ),
      2 => 
        array (
        'emailAddress' => 
        array (
          'address' => Auth::user()->email,
          'name' => Auth::user()->name,
        ),
        'type' => 'required',
      ),
    ),
  );

}else if(count($user)==3){

  $evento = array (
    'subject' => '['.$request->tipo.'] - ' . $request->titulo,
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
          'address' => $user[0]->email,
          'name' => $user[0]->name,
        ),
        'type' => 'required',
      ),
       1 => 
        array (
        'emailAddress' => 
        array (
          'address' => $user[1]->email,
          'name' => $user[1]->name,
        ),
        'type' => 'required',
      ),
       2 => 
        array (
        'emailAddress' => 
        array (
          'address' => $user[2]->email,
          'name' => $user[2]->name,
        ),
        'type' => 'required',
      ),
      3 => 
        array (
        'emailAddress' => 
        array (
          'address' => Auth::user()->email,
          'name' => Auth::user()->name,
        ),
        'type' => 'required',
      ),
    ),
  );

}else{

  $evento = array (
    'subject' => '['.$request->tipo.'] - ' . $request->titulo,
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
          'address' => $user[0]->email,
          'name' => $user[0]->name,
        ),
        'type' => 'required',
      ),
       1 => 
        array (
        'emailAddress' => 
        array (
          'address' => $user[1]->email,
          'name' => $user[1]->name,
        ),
        'type' => 'required',
      ),
       2 => 
        array (
        'emailAddress' => 
        array (
          'address' => $user[2]->email,
          'name' => $user[2]->name,
        ),
        'type' => 'required',
      ),
       3 => 
        array (
        'emailAddress' => 
        array (
          'address' => $user[3]->email,
          'name' => $user[3]->name,
        ),
        'type' => 'required',
      ),
      4 => 
        array (
        'emailAddress' => 
        array (
          'address' => Auth::user()->email,
          'name' => Auth::user()->name,
        ),
        'type' => 'required',
      ),
    ),
  );
  
}
