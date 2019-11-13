
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body style="margin: 0; padding: 0;">

    <table align="center" border="1" cellpadding="0" cellspacing="0" width="600">
        <thead>
            <tr>
                <th colspan="2">
                    <h1><b>{{strtoupper($content['tipo'])}} - {{Carbon\Carbon::today()->format('d/m/Y')}}</b></h1>
                </th>
            </tr>
            <tr>
                <th class="text-center">Horário</th>
                <th class="text-center">Usuário</th>
            </tr>
        </thead>
        
        @if(count($content['dias']) < 1 || !$content['dias'][0]["disponivel"])
            <tr class="active">
                <td colspan="3" style="text-align:center;">
                    <h3>Não haverá sessões neste dia.</h3>
                </td>
            </tr>
        @else
            @foreach($content['dias'][0]['horarios'] as $key => $horario)
                <tr>
                    <td style="text-align:center;">{{$key}} às {{Carbon\Carbon::parse($key)->addMinutes($content['tempo_sessao'])->format('H:i')}}</td>
                    <td>
                        @if($horario) {{-- se horário ocupado --}}
                            <i>Agendado por <b>{{$horario}}</b></i>
                        @endif
                    </td>
                </tr>
            @endforeach

        @endif
    </table>

</body>

</html>