<?php

$evento = array (
  'subject' => 'Massagem',
  'body' => 
  array (
    'contentType' => 'HTML',
    'content' => 'Dirija-se à sala 711 para a sua sessão de massagem.',
  ),
  'start' => 
  array (
    'dateTime' => $request->data.'T'.$request->hora,
    'timeZone' => 'E. South America Standard Time',
  ),
  'end' => 
  array (
    'dateTime' => $request->data.'T'.Carbon\Carbon::parse($request->hora)->addMinutes(10)->format('H:i:s'),
    'timeZone' => 'E. South America Standard Time',
  ),
  'reminderMinutesBeforeStart' => 10,
  'location' => 
  array (
    'displayName' => 'Sala 711, 7º Andar',
  ),
  'attendees' => 
  array (
    0 => 
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