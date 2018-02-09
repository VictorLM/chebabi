<?php

namespace Intranet\Http\Controllers\APIs;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Intranet\Andamentos;

class LegalOneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    ///////////////////////////////////////////
    private function get_cURL($parameters){

        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $parameters);
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $result = json_decode($result, TRUE);
        dd($result);
        if($response_code == 200 && !empty($result) && !isset($result['error'])){
            return($result);
        }else{
            return NULL;
        }

    }

    public function getAndamentos()
    {
        set_time_limit(600);
        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $parameters = 'https://api.thomsonreuters.com/legalone/v1/api/rest/Updates';
        $parameters .= '?$filter=originType eq ';
        $parameters .= "'";
        $parameters .= 'OfficialJournalsCrawler';
        $parameters .= "'";
        $parameters .= 'and date Ge ';
        $parameters .= '2018-02-07T00:00:00Z';
        $parameters .= '&$expand=relationships($filter=linkType eq ';
        $parameters .= "'Litigation')";
        //dd($parameters);
        $result = $this->get_cURL($parameters);

        $teste = array(
            array(
                'id' => '',
                'description' => '',
                'creationDate' => '',
                'folder' => '',
            )
        );

        $contador = 0;

        $next = TRUE;

        do {
            if(!isset($result["error"]) && !empty($result["value"])){
                foreach ($result['value'] as $key => $value) {

                    if(!empty($value['relationships'][0]['linkId'])){
                        $pasta = $this->getPasta($value['relationships'][0]['linkId']);
                    }else{
                        $pasta = NULL;
                    }
                    //PEGAR NOME CLIENTE E ÁREA DO PROCESSO
                    //////////////////////////////////
                    $pasta = $pasta['folder'];
                    $numero = $pasta['identifierNumber'];
                    $cliente = $pasta['participants'][0]['contactId'];
                    //CLIENTE E AREA
                    /////////////////////////////////
                    //INSERIR BD
                    $teste[$contador]['id'] = $value['id'];
                    $teste[$contador]['description'] = $value['description'];
                    $teste[$contador]['creationDate'] = $value['creationDate'];
                    $teste[$contador]['folder'] = $pasta;

                    $contador++;
                    //////////////////////////////////////
                }
                if(isset($result["@odata.nextLink"])){
                    $result = $this->get_cURL($result["@odata.nextLink"]);
                    $next = TRUE;
                }else{
                    $next = FALSE;
                }
            }else{
                $next = FALSE;
            }
        }while($next);
        dd($teste);
    }

    private function getPasta($linkId)
    {
        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        //$linkId = 2386;
        //$filter = '?%24expand=participants';
        $filter = '?%24expand=participants($filter=type eq ';
        $filter .= "'";
        $filter .= 'Customer';
        $filter .= "'";
        $filter .= ")";

        //$filter .= '$select=id,folder,identifierNumber,oldNumber&$expand=participants($filter=type eq ';
        //$filter .= "'Customer' or type eq 'OtherParty')";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/Lawsuits/'.$linkId.$filter);
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        
        $result = json_decode($result, TRUE);
        //dd($result);
        if($response_code == 200 && !empty($result) && !isset($result['error'])){
            return($result);
        }else{
            return NULL;
        }

    }
    ///////////////////////////////////////////

    public function andamentos_datacloud()
    {
        return ("EM DESENVOLVIMENTO");
    }

    public function andamentos()
    {
        $tipos = DB::table('tipos_andamentos_legalone')
                ->orderBy('name')
                ->get();

        $title = 'Novo Andamento | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.andamentos', compact('title', 'tipos'));
    }

    public function id_pasta(Request $request){

        $pasta = key($request->all());

        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');
        
        $filter = '?$filter=contains(folder, ';
        $filter .= "'";
        $filter .= $pasta;
        $filter .= "')&";
        $filter .= '$select=id,folder';
        
        $ch_pasta = curl_init();
        curl_setopt($ch_pasta, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/Lawsuits'.$filter.'');
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch_pasta, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_pasta, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pasta, CURLOPT_SSL_VERIFYPEER, false);
        $pasta_return = curl_exec($ch_pasta);

        curl_close($ch_pasta);
        
        $arr = json_decode($pasta_return, TRUE);

        if(!empty($arr)){
            return Response::json($arr);
        }else{
            echo('Erro! ' . $arr);
        }

    }

    public function inserir_andamentos(Request $request){

        $tipos = DB::table('tipos_andamentos_legalone')
                ->select('id')
                ->get();

        $tipos = ($tipos->toArray());

        $tipos_array = [];

        foreach($tipos as $tipo){
            $tipos_array[] = $tipo->id;
        }

        $validatedData = Validator::make($request->all(), [
            'pasta' => 'required|string|max:11',
            'pastaid' => 'required|integer',
            'tipo' => [
                'required',
                Rule::in($tipos_array),
            ],
            'descricao' => 'required|string|max:2000',
        ]);

        if (!$validatedData->fails()){

            $data = Carbon::now()->toDateTimeString().'Z';
            $data = str_replace(' ', 'T', $data);

            $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

            $parametros = 
            array (
                'typeId' => $request->tipo,
                'isSubType' => false,
                'originType' => 'Manual',
                'description' => $request->descricao,
                'notes' => 'Andamento inserido através da intranet.',
                'isConfidential' => false,
                'date' => $data,
                'relationships' => 
                array (
                0 => 
                array (
                    'linkId' => $request->pastaid,
                    'linkType' => 'Litigation',
                ),
                ),
            );

            $parametros = json_encode($parametros);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.thomsonreuters.com/legalone/v1/api/rest/Updates");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
            curl_setopt($ch, CURLOPT_POST, 1);
            //PRA DEBUGAR A RESPONSE NO CONSOLE
            //curl_setopt($ch, CURLOPT_VERBOSE, true);
            $headers = array();
            $headers[] = "Content-Type: application/json";
            $headers[] = "Accept: application/json";
            $headers[] = "Authorization: Bearer " . $token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close ($ch);
            
            $arr = json_decode($result, TRUE);
            
            if($response_code == 201 && !empty($arr) && !isset($arr['error'])){
                
                DB::table('andamentos')->insert([
                    'id' => $arr['id'],
                    'usuario' => Auth::user()->id,
                    'tipo' => $arr['typeId'],
                    'pasta' => $request->pasta,
                    'descricao' => $arr['description'],
                    'observacoes' => $arr['notes'],
                    'created_at' => Carbon::now(),
                ]);
                
                $request->session()->flash('alert-success', 'Andamento inserido na pasta '.$request->pasta.' com sucesso!');
                return redirect()->action('APIs\LegalOneController@andamentos');
            }else{
                return redirect()->back()
                    ->withInput()
                    ->withErrors(array('message' => 'Erro ao enviar! Favor informar o departamento responsável a seguinte mensagem: '. 
                                        'Response code: ' .$response_code. '. Mensagem: ' .$result));
            }
            
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    //////////////////////////////////////////////////////////////////
    //AS FUNÇÕES ABAIXO SÃO DAS CHAMADAS AJAX DO RELATÓRIO DE VIAGEM//
    //////////////////////////////////////////////////////////////////

    public function legalone_api(Request $request){

        $pasta = key($request->all());
        
        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $filter = '?$filter=contains(folder, ';
        $filter .= "'";
        $filter .= $pasta;
        $filter .= "')&";
        $filter .= '$select=id,folder,identifierNumber,oldNumber&$expand=participants($filter=type eq ';
        $filter .= "'Customer' or type eq 'OtherParty')";

        $ch_pasta = curl_init();
        curl_setopt($ch_pasta, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/Lawsuits'.$filter.'');
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch_pasta, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_pasta, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pasta, CURLOPT_SSL_VERIFYPEER, false);
        $pasta_return = curl_exec($ch_pasta);

        curl_close($ch_pasta);
        
        $arr = json_decode($pasta_return, TRUE);
        
        return Response::json($arr);
    }
    
    public function legalone_api_contacts(Request $request){

        $contactId = key($request->all());
        
        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $ch_pasta = curl_init();
        curl_setopt($ch_pasta, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/companies/'.$contactId.'?$select=id,name');
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch_pasta, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_pasta, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pasta, CURLOPT_SSL_VERIFYPEER, false);
        $pasta_return = curl_exec($ch_pasta);

        curl_close($ch_pasta);
        
        $arr = json_decode($pasta_return, TRUE);
        
        return Response::json($arr);
    }
    
    public function legalone_api_contacts_individuals(Request $request){

        $contactId = key($request->all());
        
        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $ch_pasta = curl_init();
        curl_setopt($ch_pasta, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/Individuals/'.$contactId.'?$select=name');
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch_pasta, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_pasta, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_pasta, CURLOPT_SSL_VERIFYPEER, false);
        $pasta_return = curl_exec($ch_pasta);

        curl_close($ch_pasta);
        
        $arr = json_decode($pasta_return, TRUE);
        
        return Response::json($arr);
    }
}
