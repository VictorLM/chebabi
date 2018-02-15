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

    public function criar_evento(Request $request){
        //VAI INCLUIR COMO ENVOLVIDO QUEM CRIA O EVENTO?
        //MAX 4 ENVOLVIDOS
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
            'envolvidos' => 'required|array|min:1|max:4',
            'descricao' => 'nullable|string|max:2000',
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

                $i = 0;
                $envolvido_nome0 = "";
                $envolvido_nome1 = "";
                $envolvido_nome2 = "";
                $envolvido_nome3 = "";
                $envolvido_email0 = "";
                $envolvido_email1 = "";
                $envolvido_email2 = "";
                $envolvido_email3 = "";

                while($i < 5){
                    if(isset($result['attendees'][$i])){
                        ${"envolvido_nome".$i} = $result['attendees'][$i]['emailAddress']['name'];
                        ${"envolvido_email".$i}  = $result['attendees'][$i]['emailAddress']['address'];
                    }
                    $i++;
                }
                
                if($request->tipo == 'Ausente'){
                    $color = '#E6EB0A';
                }else if($request->tipo == 'Carro'){
                    $color = '#00e600';
                }else if($request->tipo == 'Motorista'){
                    $color = '#ffa31a';
                }else if($request->tipo == 'Reunião'){
                    $color = '#80bfff';
                }else{
                    $color = NULL;
                }

                DB::table('eventos')->insert([
                    'id' => $result['id'],
                    'title' => $result['subject'],
                    'tipo' => $request->tipo,
                    'start' => Carbon::parse($result['start']['dateTime'])->format('Y-m-d H:i:s'),
                    'end' => Carbon::parse($result['end']['dateTime'])->format('Y-m-d H:i:s'),
                    'descricao' => $result['body']['content'],
                    'created_at' => Carbon::now(),
                    'organizador_nome' => Auth::user()->name,
                    'organizador_email' => Auth::user()->email,
                    'url' => "/intranet/agenda/evento/".$result['id'],
                    'allDay' => $result['isAllDay'],
                    'cancelado' => $result['isCancelled'],
                    'local' => $result['location']['displayName'],
                    'envolvido1_nome' => $envolvido_nome0,
                    'envolvido1_email' => $envolvido_email0,
                    'envolvido2_nome' => $envolvido_nome1,
                    'envolvido2_email' => $envolvido_email1,
                    'envolvido3_nome' => $envolvido_nome2,
                    'envolvido3_email' => $envolvido_email2,
                    'envolvido4_nome' => $envolvido_nome3,
                    'envolvido4_email' => $envolvido_email3,
                    'last_sync' => Carbon::now(),
                    'color' => $color,
                ]);
                
                $request->session()->flash('alert-success', 'Evento cadastrado com sucesso!');
                return redirect('/intranet/agenda/evento/'.$result['id']);
            }else{
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'Erro ao cadastrar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                        'Response code: ' .$response_code. '. Mensagem: ' .$result));
            }

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

}
