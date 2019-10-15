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
use Intranet\AndamentoDataCloud;

class LegalOneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
//////////////////////////////////

    public function custas()
    {
        $tipos = DB::table('tipos_andamentos_legalone')
                ->orderBy('name')
                ->get();

        $title = 'Novo Custo | Intranet Izique Chebabi Advogados Associados';

        return view('intranet.novo_custo', compact('title', 'tipos'));
    }

//////////////////////////////////
    public function andamentos_datacloud()
    {
        $andamentos = AndamentoDataCloud::orderBy('updated_at', 'Desc')
            ->limit(20)
            ->get();

        $title = 'Andamentos não oficiais Data Cloud Legal One | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.andamentos', compact('title', 'andamentos'));
    }

    public function andamentos_datacloud_filtrados(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'area' => [
                'nullable',
                Rule::in(['Cível', 'Trabalhista', 'BR Trabalhista']),
            ],
            'posicao' => [
                'nullable',
                Rule::in(['Autor', 'Réu', 'Interessado']),
            ],
            'pasta' => 'nullable|string|max:11',
            'cliente' => 'nullable|string|max:100',
            'data' => 'nullable|date',
        ]);

        if ($validatedData->fails()){
            return redirect()->back()->withErrors($validatedData)->withInput();
        }else{
            $area = null;
            $posicao = null;
            $pasta = null;
            $cliente = null;
            $data = null;
            
            $andamentos = AndamentoDataCloud::orderBy('updated_at', 'Desc');

            if(!empty($request->area)){
                $andamentos->where('area', $request->area);
                $area = $request->area;
            }
            if(!empty($request->posicao)){
                if($request->posicao == "Autor"){
                    $andamentos->whereIn('posicao', ['Autor', 'Reclamante', 'Recorrente']);
                }
                if($request->posicao == "Réu"){
                    $andamentos->whereIn('posicao', ['Réu', 'Reclamada','Recorrido']);
                }
                if($request->posicao == "Interessado"){
                    $andamentos->where('posicao', 'Interessado');
                }
                $posicao = $request->posicao;
            }
            if(!empty($request->pasta)){
                $andamentos->where('pasta', 'like', '%'.$request->pasta.'%');
                $pasta = $request->pasta;
            }
            if(!empty($request->cliente)){
                $andamentos->where('cliente', 'like', '%'.$request->cliente.'%');
                $cliente = $request->cliente;
            }
            if(!empty($request->data)){
                $andamentos->whereDate('updated_at', '>=', $request->data);
                $data = $request->data;
            }

            $count_andamentos = $andamentos;
            $count = $count_andamentos->count();
            
            $andamentos_filtrados = $andamentos->paginate(20);

            $title = 'Andamentos não oficiais Data Cloud Legal One | Intranet Izique Chebabi Advogados Associados';
            return view('intranet.andamentos', compact('title', 'andamentos_filtrados', 'area', 'posicao', 'pasta', 'cliente', 'data', 'count'));
        }
    }

    public function showAndamento(Request $request, $id){
        $andamento = AndamentoDataCloud::find($id);
        $title = 'Andamento não oficial Data Cloud Legal One | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.andamento', compact('title', 'andamento'));
    }

    public function andamentos()
    {
        $tipos = DB::table('tipos_andamentos_legalone')
                ->orderBy('name')
                ->get();

        $title = 'Novo Andamento | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.novo_andamento', compact('title', 'tipos'));
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
    ///////////////////////////////////////////////////////////
    public function inserir_custas(Request $request){

        $validatedData = Validator::make($request->all(), [
            'pasta' => 'required|string|max:11',
            'pastaid' => 'required|integer',
            'data' => 'required|date',
            'valor' => 'required|numeric|max:-1',////TESTAR
            'tipo' => 'required|in:157',
            'descricao' => 'required|string|max:200',
            'credor' => 'required|in:15408,9444,14891',
            'obs' => 'required|in:Enviado ao cliente,Recolhido pelo escritório',
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
    ///////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////
    // AS FUNÇÕES ABAIXO SÃO DAS CHAMADAS AJAX DO RELATÓRIO DE VIAGEM //
    ////////////////////////////////////////////////////////////////////

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
        curl_setopt($ch_pasta, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/litigations'.$filter.'');
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

    public function get_credores(Request $request){

        $ids = ['15408', '9444', '14891'];
        //15408 PODER JUDICIÁRIO
        //9444  TRIBUNAL DE JUSTIÇA
        //14891 GOVERNO DO ESTADO DE SÃO PAULO

        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $result = [
            array()
        ];

        foreach ($ids as $id){

            $ch_pasta = curl_init();
            curl_setopt($ch_pasta, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/Companies/'.$id.'?$select=id,name');
            $headers = array('Authorization: Bearer ' . $token);
            curl_setopt($ch_pasta, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch_pasta, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_pasta, CURLOPT_SSL_VERIFYPEER, false);
            $return = curl_exec($ch_pasta);

            $response_code = curl_getinfo($ch_pasta, CURLINFO_HTTP_CODE);
            
            curl_close($ch_pasta);

            $arr = json_decode($return, TRUE);

            if($response_code == 200 && !empty($arr) && !isset($arr['error'])){
                array_push($result, $arr);
            }
        }

        if(count($result)>1){
            unset($result[0]);
            $result = array_merge($result); 
            return Response::json($result);
        }else{
            return null;
        }

    }

    public function get_tipos_gastos(){

        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $id = 157;
        //Gastos com Clientes / Custas

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/FinanceChartOfAccountsCategories/'.$id.'?$select=id,name');
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $return = curl_exec($ch);

        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $arr = json_decode($return, TRUE);

        if($response_code == 200 && !empty($arr) && !isset($arr['error'])){
            return Response::json($arr);
        }else{
            return null;
        }
        
    }

}
