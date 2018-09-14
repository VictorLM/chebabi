<?php

namespace Intranet\Http\Controllers\APIs;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Intranet\Eventos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Intranet\Massagem;

class MicrosoftController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function criar_evento(Request $request){
        //dd($request);
        $validatedData = Validator::make($request->all(), [
            'titulo' => 'required|string|max:200',
            'tipo' => [
                'required',
                Rule::in(['Ausente', 'Motorista', 'Reunião', 'Carro', 'Outro']),
            ],
            'local' => 'required|string|max:100',
            'iniciodata' => 'required|date_format:Y-m-d|after_or_equal:today',
            'iniciohora' => 'required|date_format:H:i',
            'terminodata' => 'required|date_format:Y-m-d|after_or_equal:today',
            'terminohora' => 'required|date_format:H:i',
            'envolvidos' => 'nullable|array|min:1|max:60', //MAX 60 ENVOLVIDOS
            'descricao' => 'nullable|string|max:2000',
            'recorrencia' => 'nullable|array|max:5'
        ]);
        
        if (!$validatedData->fails()){

            if(!empty($request->envolvidos)){
                $user = DB::table('users')
                ->select('id', 'name', 'email')
                ->whereIn('id', $request->envolvidos)
                ->get();
            }else{
                $user = NULL;
            }

            $token = DB::table('apikeys')
            ->select('token')
            ->where('name', 'Microsoft Graph')
            ->first();
        
            require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/evento.json.php';

            $evento = json_encode($evento);
            //dd(json_decode($evento));
            $parameters = 'https://graph.microsoft.com/v1.0/users/agenda@chebabi.com/events';
            
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $parameters);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $evento);
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = "Authorization: Bearer ".$token->token;
            $headers[] = "Content-Type:application/json";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ($ch);
            
            $result = json_decode($result,true);

            if($response_code == 201 && !empty($result)){

                if($request->tipo == 'Ausente'){
                    $color = 'yellow';
                }else if($request->tipo == 'Carro'){
                    $color = '#00e600';
                }else if($request->tipo == 'Motorista'){
                    $color = '#ffa31a';
                }else if($request->tipo == 'Reunião'){
                    $color = '#80bfff';
                }else{
                    $color = NULL;
                }
                
                if(!empty($request->recorrencia)){
                    foreach($request->recorrencia as $dia){
                        if($dia == "Monday"){
                            $dow[] = 1;
                        }elseif($dia == "Tuesday"){
                            $dow[] = 2;
                        }elseif($dia == "Wednesday"){
                            $dow[] = 3;
                        }elseif($dia == "Thursday"){
                            $dow[] = 4;
                        }elseif($dia == "Friday"){
                            $dow[] = 5;
                        }
                    }
                }else{
                    $dow = null;
                }
                
                DB::table('eventos')->insert([
                    'id' => $result['id'],
                    'title' => $result['subject'],
                    'tipo' => $request->tipo,
                    'start' => Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s'),
                    'end' => Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s'),
                    'descricao' => preg_replace("/\r|\n/", "", strip_tags($result['body']['content'])),
                    'created_at' => Carbon::now(),
                    'organizador_nome' => Auth::user()->name,
                    'organizador_email' => Auth::user()->email,
                    'url' => "/intranet/agenda/evento/".$result['id'],
                    'allDay' => $result['isAllDay'],
                    'cancelado' => $result['isCancelled'],
                    'local' => $result['location']['displayName'],
                    'envolvidos' => serialize($result['attendees']),
                    'color' => $color,
                    'dow' => serialize($dow),
                ]);
                
                $request->session()->flash('alert-success', 'Evento cadastrado com sucesso!');
                return redirect('/intranet/agenda/evento/'.$result['id']);
            }else{
                if(!empty($result['error'])){
                    $mensagem = $result['error']['message'];
                    $mensagem .= '/ '.$result['error']['code'].'.';
                }else{
                    $mensagem = 'NULL';
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'Erro ao cadastrar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                        'Response code: ' .$response_code. '. Mensagem: ' .$mensagem));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function update_evento(Request $request){

        $evento = Eventos::find($request->id);

        if(Auth::user()->email == $evento->organizador_email || 
            Auth::user()->tipo == 'admin' || 
            Auth::user()->email == 'recepcao@chebabi.com' &&
            //$evento->end > Carbon::now() && //NÃO SE APLICA AOS EVENTOS RECORRENTES
            !$evento->cancelado){

            $validatedData = Validator::make($request->all(), [
                'id' => 'required|string',
                'titulo' => 'required|string|max:200',
                'tipo' => [
                    'required',
                    Rule::in(['Ausente', 'Motorista', 'Reunião', 'Carro', 'Outro']),
                ],
                'local' => 'required|string|max:100',
                //'iniciodata' => 'required|date_format:Y-m-d|after_or_equal:today', //NÃO SE APLICA AOS EVENTOS RECORRENTES
                'iniciodata' => 'required|date_format:Y-m-d',
                'iniciohora' => 'required|date_format:H:i',
                //'terminodata' => 'required|date_format:Y-m-d|after_or_equal:today', //NÃO SE APLICA AOS EVENTOS RECORRENTES
                'terminodata' => 'required|date_format:Y-m-d', 
                'terminohora' => 'required|date_format:H:i',
                'envolvidos' => 'nullable|array|min:1|max:60', //MAX 60 ENVOLVIDOS
                'descricao' => 'nullable|string|max:2000',
                'recorrencia' => 'nullable|array|max:5'
            ]);
    
            if (!$validatedData->fails()){
    
                if(!empty($request->envolvidos)){
                    $user = DB::table('users')
                    ->select('id', 'name', 'email')
                    ->whereIn('id', $request->envolvidos)
                    ->get();
                }else{
                    $user = NULL;
                }
    
                $token = DB::table('apikeys')
                ->select('token')
                ->where('name', 'Microsoft Graph')
                ->first();

                if(!empty($request->recorrencia)){
                    foreach($request->recorrencia as $dia){
                        if($dia == "Monday"){
                            $dow[] = 1;
                        }elseif($dia == "Tuesday"){
                            $dow[] = 2;
                        }elseif($dia == "Wednesday"){
                            $dow[] = 3;
                        }elseif($dia == "Thursday"){
                            $dow[] = 4;
                        }elseif($dia == "Friday"){
                            $dow[] = 5;
                        }
                    }
                }else{
                    $dow = null;
                }
            
                require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/evento_update.json.php';

                $evento_atualizado = json_encode($evento_atualizado);
                //dd(json_decode($evento_atualizado));
                $parameters = 'https://graph.microsoft.com/v1.0/users/agenda@chebabi.com/events/'.$request->id;
                
                $ch = curl_init();
    
                curl_setopt($ch, CURLOPT_URL, $parameters);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $evento_atualizado);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    
                $headers = array();
                $headers[] = "Authorization: Bearer ".$token->token;
                $headers[] = "Content-Type:application/json";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
                $result = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ($ch);
                
                $result = json_decode($result,true);
                
                if($response_code == 200 && !empty($result)){
    
                    if($request->tipo == 'Ausente'){
                        $color = 'yellow';
                    }else if($request->tipo == 'Carro'){
                        $color = '#00e600';
                    }else if($request->tipo == 'Motorista'){
                        $color = '#ffa31a';
                    }else if($request->tipo == 'Reunião'){
                        $color = '#80bfff';
                    }else{
                        $color = NULL;
                    }

                    $alterado = '* Evento alterado pelo usuário '.Auth::user()->name.' em '.Carbon::parse(Carbon::now())->format('d/m/Y H:i').'.';
    
                    Eventos::where('id', $request->id)
                        ->update([
                            'title' => $result['subject'],
                            'tipo' => $request->tipo,
                            'start' => Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s'),
                            'end' => Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s'),
                            'descricao' => preg_replace("/\r|\n/", "", strip_tags($result['body']['content'])),
                            'updated_at' => Carbon::now(),
                            'allDay' => $result['isAllDay'],
                            'cancelado' => $result['isCancelled'],
                            'local' => $result['location']['displayName'],
                            'envolvidos' => serialize($result['attendees']),
                            'color' => $color,
                            'alterado' => $alterado,
                            'dow' => serialize($dow),
                        ]);
    
                    $request->session()->flash('alert-success', 'Evento atualizado com sucesso!');
                    return redirect('/intranet/agenda/evento/'.$request->id);
                }else{
                    if(!empty($result['error'])){
                        $mensagem = $result['error']['message'];
                        $mensagem .= '/ '.$result['error']['code'].'.';
                    }else{
                        $mensagem = 'NULL';
                    }
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(array('message' => 'Erro ao atualizar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                            'Response code: ' .$response_code. '. Mensagem: ' .$mensagem));
                }
    
            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }

        }else{
            return null;
        }

    }

    public function cancela_evento(Request $request, $id){

        $evento = Eventos::find($id);

        if(Auth::user()->email == $evento->organizador_email || 
            Auth::user()->tipo == 'admin' || 
            Auth::user()->email == 'recepcao@chebabi.com' &&
            $evento->end > Carbon::now() && 
            !$evento->cancelado){
        
                $token = DB::table('apikeys')
                ->select('token')
                ->where('name', 'Microsoft Graph')
                ->first();

                require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/evento_cancelado.json.php';

                $evento_cancelado = json_encode($evento_cancelado);

                $parameters = 'https://graph.microsoft.com/beta/users/agenda@chebabi.com/events/'.$id.'/cancel';
                
                $ch = curl_init();
    
                curl_setopt($ch, CURLOPT_URL, $parameters);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $evento_cancelado);
                curl_setopt($ch, CURLOPT_POST, 1);
    
                $headers = array();
                $headers[] = "Authorization: Bearer ".$token->token;
                $headers[] = "Content-Type:application/json";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
                $result = curl_exec($ch);

                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ($ch);
                
                $result = json_decode($result,true);
                
                if($response_code == 202){

                    $alterado = '* Evento cancelado pelo usuário '.Auth::user()->name.' em '.Carbon::parse(Carbon::now())->format('d/m/Y H:i').'.';
    
                    Eventos::where('id', $id)
                        ->update([
                            'updated_at' => Carbon::now(),
                            'cancelado' => TRUE,
                            'color' => 'red',
                            'alterado' => $alterado,
                        ]);
    
                    $request->session()->flash('alert-success', 'Evento cancelado com sucesso!');
                    return redirect('/intranet/agenda/evento/'.$id);
                }else{
                    if(!empty($result['error'])){
                        $mensagem = $result['error']['message'];
                        $mensagem .= '/ '.$result['error']['code'].'.';
                    }else{
                        $mensagem = 'NULL';
                    }
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(array('message' => 'Erro ao cancelar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                            'Response code: ' .$response_code. '. Mensagem: ' .$mensagem));
                }

        }else{
            return null;
        }
    }

    public function criar_evento_massagem(Request $request){

        $validatedData = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s',
        ]);
        
        if (!$validatedData->fails()){

            //VALIDAÇÃO COM MASSAGENS JÁ AGENDADAS E LIMITE DO USUÁRIO
            $dias_sem_massagem = DB::table('dias_sem_massagens')
                ->whereDate('data', $request->data)
                ->count();

            $massagens_usuario_mes = Massagem::where('cancelado', false)
                ->where('usuario', Auth::user()->id)
                ->whereMonth('inicio_data', Carbon::now()->month)
                ->whereYear('inicio_data', Carbon::now()->year)
                ->count();

            $massagens_usuario_dia = Massagem::where('cancelado', false)
                ->where('usuario', Auth::user()->id)
                ->whereDate('inicio_data', $request->data)
                ->count();

            $massagem_livre = Massagem::where('cancelado', false)
                ->whereDate('inicio_data', $request->data)
                ->whereTime('inicio_hora', '=', $request->hora)
                ->count();

            if(!$dias_sem_massagem && $massagens_usuario_mes < 4 && !$massagens_usuario_dia && !$massagem_livre){
                
                $token = DB::table('apikeys')
                    ->select('token')
                    ->where('name', 'Microsoft Graph')
                    ->first();
        
                require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/massagem.json.php';
                
                $evento = json_encode($evento);
                
                $parameters = 'https://graph.microsoft.com/v1.0/users/massagem@chebabi.com/events';
                
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $parameters);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $evento);
                curl_setopt($ch, CURLOPT_POST, 1);

                $headers = array();
                $headers[] = "Authorization: Bearer ".$token->token;
                $headers[] = "Content-Type:application/json";
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ($ch);
                
                $result = json_decode($result,true);

                if($response_code == 201 && !empty($result)){

                    Massagem::insert([
                        'evento_id' => $result['id'],
                        'usuario' => Auth::user()->id,
                        'inicio_data' => Carbon::parse($result['start']['dateTime'])->format('Y-m-d'),
                        'inicio_hora' => Carbon::parse($result['start']['dateTime'])->format('H:i:s'),
                        'fim_data' => Carbon::parse($result['end']['dateTime'])->format('Y-m-d'),
                        'fim_hora' => Carbon::parse($result['end']['dateTime'])->format('H:i:s'),
                        'cancelado' => false,
                        'created_at' => Carbon::now(),
                    ]);
                    
                    $request->session()->flash('alert-success', 'Massagem agendada com sucesso!');
                    return redirect('/intranet/agendamento-massagem');
                }else{
                    if(!empty($result['error'])){
                        $mensagem = $result['error']['message'];
                        $mensagem .= '/ '.$result['error']['code'].'.';
                    }else{
                        $mensagem = 'NULL';
                    }
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(array('message' => 'Erro ao agendar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                            'Response code: ' .$response_code. '. Mensagem: ' .$mensagem));
                }
                
            }else{
                return redirect()->back()
                        ->withInput()
                        ->withErrors(array('message' => 'Erro ao agendar! Conflito com o dia e/ou horário selecionado. Tente novamente.'));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function cancelar_evento_massagem(Request $request){

        $validatedData = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s',
        ]);
        
        if (!$validatedData->fails()){

            $data_horario_futuro = false;

            if($request->data == Carbon::parse(Carbon::today())->format('Y-m-d') && $request->hora > Carbon::parse(Carbon::now())->format('H:i:s')){
                $data_horario_futuro = true;
            }elseif($request->data > Carbon::parse(Carbon::today())->format('Y-m-d')){
                $data_horario_futuro = true;
            }

            if($data_horario_futuro){
                $massagem = Massagem::where('cancelado', false)
                    ->where('usuario', Auth::user()->id)
                    ->whereDate('inicio_data', $request->data)
                    ->whereTime('inicio_hora', '=', $request->hora)
                    ->first();

                if($massagem->count() > 0){
                    
                    $token = DB::table('apikeys')
                        ->select('token')
                        ->where('name', 'Microsoft Graph')
                        ->first();

                    $evento_cancelado = array (
                        'Comment' => 'Sessão de massagem cancelada.'
                    );
                    
                    $evento_cancelado = json_encode($evento_cancelado);

                    $parameters = 'https://graph.microsoft.com/beta/users/massagem@chebabi.com/events/'.$massagem->evento_id.'/cancel';
                    
                    $ch = curl_init();
        
                    curl_setopt($ch, CURLOPT_URL, $parameters);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $evento_cancelado);
                    curl_setopt($ch, CURLOPT_POST, 1);
        
                    $headers = array();
                    $headers[] = "Authorization: Bearer ".$token->token;
                    $headers[] = "Content-Type:application/json";
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
                    $result = curl_exec($ch);

                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close ($ch);
                    
                    $result = json_decode($result,true);
                    
                    if($response_code == 202){
        
                        $massagem->cancelado = TRUE;
                        $massagem->save(); 
        
                        $request->session()->flash('alert-success', 'Agendamento cancelado com sucesso!');
                        return redirect('/intranet/agendamento-massagem');
                    }else{
                        if(!empty($result['error'])){
                            $mensagem = $result['error']['message'];
                            $mensagem .= '/ '.$result['error']['code'].'.';
                        }else{
                            $mensagem = 'NULL';
                        }
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(array('message' => 'Erro ao cancelar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                                'Response code: ' .$response_code. '. Mensagem: ' .$mensagem));
                    }

                }else{
                    return redirect()->back()
                            ->withInput()
                            ->withErrors(array('message' => 'Erro ao cancelar! Agendamento não encontrado. Tente novamente mais tarde ou informe este erro ao departamento responsável.'));
                }
            }else{
                return redirect()->back()
                ->withInput()
                ->withErrors(array('message' => 'Erro ao cancelar! Agendamento com data e/ou horário passados. Tente novamente mais tarde ou informe este erro ao departamento responsável.'));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

    }

}
