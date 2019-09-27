<?php

if($tipo == "quick_massages"){
  $terapia = "Quick Massage";
}else if($tipo == "auriculoterapias"){
  $terapia = "Auriculoterapia";
}else if($tipo == "massagens_pes"){
  $terapia = "Massagens nos Pés";
}else if($tipo == "massagens_relaxantes"){
  $terapia = "Massagem Relaxante";
}else{
  $terapia = "Terapia";
}

$evento = array (
  'subject' => 'Sessão de '.$terapia,
  'body' => 
  array (
    'contentType' => 'HTML',
    'content' => 'Dirija-se à sala de terapias.',
  ),
  'start' => 
  array (
    'dateTime' => $dia.'T'.$horario_inicio,
    'timeZone' => 'E. South America Standard Time',
  ),
  'end' => 
  array (
    'dateTime' => $dia.'T'.Carbon\Carbon::parse($horario_inicio)->addMinutes($tempo_sessao)->format('H:i:s'),
    'timeZone' => 'E. South America Standard Time',
  ),
  'reminderMinutesBeforeStart' => $lembrete_minutos_antes_inicio,
  'location' => 
  array (
    'displayName' => 'Sala de terapias',
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