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

class MicrosoftController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    //cURL
    public static function curl($url, $evento_json, $request_type){
        $token = DB::table('apikeys')
            ->where('name', 'Microsoft Graph')
            ->value('token');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $evento_json);
        if($request_type == "POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
        }else if($request_type == "PATCH"){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        }
        
        $headers = array();
        $headers[] = "Authorization: Bearer ".$token;
        $headers[] = "Content-Type:application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $erro["error"] = "Erro ao fazer a requisição cURL. Informe ao dpto. responsável.";
            return $erro;
        }
        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        
        $result = json_decode($result,true);
        
        if($response_code == 200 || $response_code == 201 || $response_code == 202){
            return ($result);
        }else{
            if(!empty($result['error']['message'])){
                $mensagem = $result['error']['message'];
            }else{
                $mensagem = 'NULL';
            }
            $erro["error"] = 'Erro! Response code: ' .$response_code. '. Erro: ' .$mensagem;
            return $erro;
        }
    }

    ////////// FUNÇÕES EVENTOS AGENDA@CHEBABI.COM //////////

    //CHECA DISPONIBILIDADE SALA DE REUNIÃO
    public function checagem_reuniao(Request $request){
        if($request->local == "Sala 1" || $request->local == "Sala 2"){
            //PEGA SÓ OS EVENTOS NÃO CANCELADOs, DO TIPO REUNIÃO E COM SALA ESPECIFICADA
            $eventos = Eventos::select("id","start","end")
                        ->where('cancelado', false)
                        ->where('tipo', 'Reunião')
                        ->where('local', $request->local)
                        ->whereDate('start', $request->iniciodata);
                        //O CERTO SERIA FAZER A LÓGICA COM A DATA DE TÉRMINO TAMBÉM, MAS REUNIÕES GERALMENTE ACABAM NO MESMO DIA
                        
            if($request->has('id')){ 
                $eventos->where('id','!=',$request->id); 
            };

            $eventos = $eventos->get();

            $conflito = false;

            foreach($eventos as $evento){
                
                if
                //REQUEST   |----------|
                //EVENTO    |----------|
                (Carbon::parse($request->iniciohora)->format('H:i') >= Carbon::parse($evento->start)->format('H:i')  && 
                Carbon::parse($request->terminohora)->format('H:i') <= Carbon::parse($evento->end)->format('H:i')){
                    $conflito = true;
                }else if
                //REQUEST   |----------|---|
                //EVENTO        |----------|
                (Carbon::parse($request->iniciohora)->format('H:i') <= Carbon::parse($evento->start)->format('H:i') && 
                    Carbon::parse($request->terminohora)->format('H:i') <= Carbon::parse($evento->end)->format('H:i')  && 
                    Carbon::parse($request->iniciohora)->format('H:i') < Carbon::parse($evento->end)->format('H:i') &&
                    Carbon::parse($request->terminohora)->format('H:i') > Carbon::parse($evento->start)->format('H:i')){
                    $conflito = true;
                }else if
                //REQUEST   |---|----------|
                //EVENTO    |----------|
                (Carbon::parse($request->iniciohora)->format('H:i') >= Carbon::parse($evento->start)->format('H:i') && 
                    Carbon::parse($request->terminohora)->format('H:i') >= Carbon::parse($evento->end)->format('H:i')  && 
                    Carbon::parse($request->iniciohora)->format('H:i') < Carbon::parse($evento->end)->format('H:i') && 
                    Carbon::parse($request->terminohora)->format('H:i') > Carbon::parse($evento->start)->format('H:i')){
                    $conflito = true;
                }else if
                //REQUEST   |------------------|
                //EVENTO        |----------|
                (Carbon::parse($request->iniciohora)->format('H:i') < Carbon::parse($evento->start)->format('H:i') && 
                    Carbon::parse($request->terminohora)->format('H:i') > Carbon::parse($evento->end)->format('H:i')){
                    $conflito = true;
                }
                else if
                //REQUEST       |----------|
                //EVENTO    |------------------|
                (Carbon::parse($request->iniciohora)->format('H:i') > Carbon::parse($evento->start)->format('H:i') && 
                    Carbon::parse($request->terminohora)->format('H:i') < Carbon::parse($evento->end)->format('H:i')){
                    $conflito = true;
                }
            }
                
            return ($conflito);

        }else{
            return false;
        }
    }

    public function criar_evento(Request $request){
        $validatedData = Validator::make($request->all(), [
            'titulo' => 'required|string|max:200',
            'tipo' => [
                'required',
                Rule::in(['Ausente', 'Reunião', 'Carro', 'Outro']),
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

            /////////
            require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/evento.json.php';
            $evento_json = json_encode($evento);
            $url = 'https://graph.microsoft.com/v1.0/users/agenda@chebabi.com/events';
            /////////

            //SE TIPO REUNIÃO, CHECA DISPONIBILIDADE SALA
            if($request->tipo == 'Reunião'){

                $conflito_reuniao = $this->checagem_reuniao($request);
                if(!$conflito_reuniao){

                    //cURL
                    $result = $this->curl($url, $evento_json, "POST");

                    if(empty($result["error"])){
                        
                        if($request->tipo == 'Ausente'){
                            $color = 'yellow';
                        }else if($request->tipo == 'Carro'){
                            $color = '#00e600';
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

                        $evento = new Eventos;

                        $evento->id = $result['id'];
                        $evento->title = $result['subject'];
                        $evento->tipo = $request->tipo;
                        $evento->start = Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s');
                        $evento->end = Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s');
                        $evento->descricao = preg_replace("/\r|\n/", "", strip_tags($result['body']['content']));
                        $evento->created_at = Carbon::now();
                        $evento->organizador_nome = Auth::user()->name;
                        $evento->organizador_email = Auth::user()->email;
                        $evento->url = "/intranet/agenda/evento/".$result['id'];
                        $evento->allDay = $result['isAllDay'];
                        $evento->cancelado = $result['isCancelled'];
                        $evento->local = $result['location']['displayName'];
                        $evento->envolvidos = serialize($result['attendees']);
                        $evento->color = $color;
                        $evento->dow = serialize($dow);

                        if(!$evento->save()){
                            return abort(403, 'Erro ao salvar no banco de dados.');
                        }else{
                            $request->session()->flash('alert-success', 'Evento cadastrado com sucesso!');
                            return redirect('/intranet/agenda/evento/'.$result['id']);
                        }
                        
                    }else{
                        return redirect()->back()->withInput()->withErrors(array('message' => $result['error']));
                    }

                }else{
                    return redirect()->back()->withInput()->withErrors(array('message' => 'Há uma reunião já agendada que conflita com a sala, data e horário selecionado.'));
                }
            //SE NÃO TIPO REUNIÃO
            }else{
                //cURL
                $result = $this->curl($url, $evento_json, "POST");

                if(empty($result["error"])){
                    
                    if($request->tipo == 'Ausente'){
                        $color = 'yellow';
                    }else if($request->tipo == 'Carro'){
                        $color = '#00e600';
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

                    $evento = new Eventos;

                    $evento->id = $result['id'];
                    $evento->title = $result['subject'];
                    $evento->tipo = $request->tipo;
                    $evento->start = Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s');
                    $evento->end = Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s');
                    $evento->descricao = preg_replace("/\r|\n/", "", strip_tags($result['body']['content']));
                    $evento->created_at = Carbon::now();
                    $evento->organizador_nome = Auth::user()->name;
                    $evento->organizador_email = Auth::user()->email;
                    $evento->url = "/intranet/agenda/evento/".$result['id'];
                    $evento->allDay = $result['isAllDay'];
                    $evento->cancelado = $result['isCancelled'];
                    $evento->local = $result['location']['displayName'];
                    $evento->envolvidos = serialize($result['attendees']);
                    $evento->color = $color;
                    $evento->dow = serialize($dow);

                    if(!$evento->save()){
                        return abort(403, 'Erro ao salvar no banco de dados.');
                    }else{
                        $request->session()->flash('alert-success', 'Evento cadastrado com sucesso!');
                        return redirect('/intranet/agenda/evento/'.$result['id']);
                    }
                    
                }else{
                    return redirect()->back()->withInput()->withErrors(array('message' => $result['error']));
                }

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
                    Rule::in(['Ausente', 'Reunião', 'Carro', 'Outro']),
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

                /////
                require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/evento_update.json.php';
                $evento_atualizado = json_encode($evento_atualizado);
                $url = 'https://graph.microsoft.com/v1.0/users/agenda@chebabi.com/events/'.$request->id;
                /////

                //SE TIPO REUNIÃO, CHECA DISPONIBILIDADE SALA
                if($request->tipo == 'Reunião'){

                    $conflito_reuniao = $this->checagem_reuniao($request);
                    if(!$conflito_reuniao){

                        //cURL
                        $result = $this->curl($url, $evento_atualizado, "PATCH");

                        if(empty($result["error"])){
                        
                            $color = '#80bfff';

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

                            $alterado = '* Evento alterado pelo usuário '.Auth::user()->name.' em '.Carbon::parse(Carbon::now())->format('d/m/Y H:i').'.';

                            $evento = Eventos::find($request->id);

                            $evento->title = $result['subject'];
                            $evento->tipo = $request->tipo;
                            $evento->start = Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s');
                            $evento->end = Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s');
                            $evento->descricao = preg_replace("/\r|\n/", "", strip_tags($result['body']['content']));
                            $evento->updated_at = Carbon::now();
                            $evento->url = "/intranet/agenda/evento/".$result['id'];
                            $evento->allDay = $result['isAllDay'];
                            $evento->cancelado = $result['isCancelled'];
                            $evento->local = $result['location']['displayName'];
                            $evento->envolvidos = serialize($result['attendees']);
                            $evento->color = $color;
                            $evento->dow = serialize($dow);

                            if(!$evento->save()){
                                return abort(403, 'Erro ao salvar no banco de dados.');
                            }else{
                                $request->session()->flash('alert-success', 'Evento alterado com sucesso!');
                                return redirect('/intranet/agenda/evento/'.$result['id']);
                            }
                            
                        }else{
                            return redirect()->back()->withInput()->withErrors(array('message' => $result['error']));
                        }
                        
                    }else{
                        return redirect()->back()->withInput()->withErrors(array('message' => 'Há uma reunião já agendada que conflita com a sala, data e horário selecionado.'));
                    }

                }
                //SE NÃO TIPO REUNIÃO
                else{

                    //cURL
                    $result = $this->curl($url, $evento_atualizado, "PATCH");

                    if(empty($result["error"])){
                        
                        if($request->tipo == 'Ausente'){
                            $color = 'yellow';
                        }else if($request->tipo == 'Carro'){
                            $color = '#00e600';
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

                        $alterado = '* Evento alterado pelo usuário '.Auth::user()->name.' em '.Carbon::parse(Carbon::now())->format('d/m/Y H:i').'.';

                        $evento = Eventos::find($request->id);

                        $evento->title = $result['subject'];
                        $evento->tipo = $request->tipo;
                        $evento->start = Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s');
                        $evento->end = Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s');
                        $evento->descricao = preg_replace("/\r|\n/", "", strip_tags($result['body']['content']));
                        $evento->updated_at = Carbon::now();
                        $evento->url = "/intranet/agenda/evento/".$result['id'];
                        $evento->allDay = $result['isAllDay'];
                        $evento->cancelado = $result['isCancelled'];
                        $evento->local = $result['location']['displayName'];
                        $evento->envolvidos = serialize($result['attendees']);
                        $evento->color = $color;
                        $evento->dow = serialize($dow);

                        if(!$evento->save()){
                            return abort(403, 'Erro ao salvar no banco de dados.');
                        }else{
                            $request->session()->flash('alert-success', 'Evento alterado com sucesso!');
                            return redirect('/intranet/agenda/evento/'.$result['id']);
                        }
                        
                    }else{
                        return redirect()->back()->withInput()->withErrors(array('message' => $result['error']));
                    }
                }

            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }
        }else{
            return abort(403, 'Evento inválido ou seu usuário não está autorizado.');
        }

    }

    public function cancela_evento(Request $request, $id){

        $evento = Eventos::find($id);

        if(Auth::user()->email == $evento->organizador_email || 
            Auth::user()->tipo == 'admin' || 
            Auth::user()->email == 'recepcao@chebabi.com' &&
            $evento->end > Carbon::now() && 
            !$evento->cancelado){

                require_once __DIR__ . '/../../../../app/Http/Controllers/APIs/evento_cancelado.json.php';

                $evento_cancelado = json_encode($evento_cancelado);

                $url = 'https://graph.microsoft.com/beta/users/agenda@chebabi.com/events/'.$id.'/cancel';
                
                //cURL
                $result = $this->curl($url, $evento_cancelado, "POST");

                if(empty($result["error"])){

                    $alterado = '* Evento cancelado pelo usuário '.Auth::user()->name.' em '.Carbon::parse(Carbon::now())->format('d/m/Y H:i').'.';

                    $evento->updated_at = Carbon::now();
                    $evento->cancelado = TRUE;
                    $evento->color = 'red';
                    $evento->alterado = $alterado;

                    if(!$evento->save()){
                        return abort(403, 'Erro ao salvar no banco de dados.');
                    }else{
                        $request->session()->flash('alert-success', 'Evento cancelado com sucesso!');
                        return redirect('/intranet/agenda/evento/'.$id);
                    }

                }else{
                    return redirect()->back()->withInput()->withErrors(array('message' => $result['error']));
                }
        }else{
            return abort(403, 'Evento inválido ou seu usuário não está autorizado.');
        }
    }

}
