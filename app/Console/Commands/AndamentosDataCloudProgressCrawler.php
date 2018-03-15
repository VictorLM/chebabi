<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Intranet\AndamentoDataCloud;

class AndamentosDataCloudProgressCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AndamentosDataCloudProgressCrawler:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza os andamentos do tipo ProgressCrawler pegos pelo DataCloud do Legal One com o BD local.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

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

        if($response_code == 200 && !empty($result) && !isset($result['error'])){
            return($result);
        }else{
            return NULL;
        }

    }

    private function getPasta($linkId)
    {
        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/Lawsuits/'.$linkId;
        $filter .= '?%24expand=participants($filter=type eq ';
        $filter .= "'Customer' or type eq 'OtherParty')";

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/Appeals/'.$linkId;
        $filter .= '?%24expand=participants($filter=type eq ';
        $filter .= "'Customer' or type eq 'OtherParty')";

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/proceduralIssues/'.$linkId;
        $filter .= '?%24expand=participants($filter=type eq ';
        $filter .= "'Customer' or type eq 'OtherParty')";

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        return null;
    }

    private function getArea($id)
    {
        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/Areas/'.$id;

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        return null;
    }

    private function getPosicao($id)
    {
        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/LitigationParticipantPositions/'.$id;

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        return null;
    }

    private function getNomes($id){

        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/companies/'.$id.'?$select=id,name';

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        $filter = 'https://api.thomsonreuters.com/legalone/v1/api/rest/individuals/'.$id.'?$select=id,name';

        $result = $this->get_cURL($filter);

        if($result != null){
            return ($result);
        }

        return null;
    }

    public function handle()
    {
        set_time_limit(7200);//DUAS HORAS

        $data = Carbon::now()->toDateString().'T00:00:00Z';
        //$data = '2018-03-13T00:00:00Z';

        $parameters = 'https://api.thomsonreuters.com/legalone/v1/api/rest/Updates';
        $parameters .= '?$filter=originType eq ';
        $parameters .= "'";
        //$parameters .= 'OfficialJournalsCrawler'; //ANDAMENTOS DE DIÁRIOS OFICIAIS
        $parameters .= 'ProgressesCrawler'; //ANDAMENTOS DE SITES DOS TRIBUNAIS
        $parameters .= "'";
        $parameters .= 'and creationDate Ge ';
        $parameters .= $data;
        $parameters .= '&$expand=relationships($filter=linkType eq ';
        $parameters .= "'Litigation')";
        $result = $this->get_cURL($parameters);

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

                    if(!empty($pasta['identifierNumber'])){
                        $processo = $pasta['identifierNumber'];
                    }else if(!empty($pasta['oldNumber'])){
                        $processo = $pasta['oldNumber'];
                    }

                    $cliente = $this->getNomes($pasta['participants'][0]['contactId']);

                    $posicao = $this->getPosicao($pasta['participants'][0]['positionId']);

                    if(count($pasta['participants'])>1){
                        $contrario = $this->getNomes($pasta['participants'][1]['contactId']);
                        $contrario = $contrario['name'];
                    }else{
                        $contrario = null;
                    }

                    $area = $this->getArea($pasta['responsibleOfficeId']);
                    $area = substr($area['path'], strrpos($area['path'], '/') + 2);

                    AndamentoDataCloud::firstOrCreate(
                        ['id' => $value['id']],
                        [
                            'id' => $value['id'],
                            'pasta' => $pasta['folder'],
                            'pasta_id' => $pasta['id'],
                            'data_cadastro_pasta' => Carbon::parse($pasta['creationDate'])->format('Y-m-d H:i:s'),
                            'processo' => $processo,
                            'cliente' => $cliente['name'],
                            'posicao' => $posicao['name'],
                            'contrario' =>  $contrario,
                            'area' =>  $area,
                            'descricao' =>  $value['description'],
                            'created_at' => Carbon::parse($value['date'])->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::parse($value['creationDate'])->format('Y-m-d H:i:s'),
                            'last_sync' => Carbon::now(),
                        ]
                    );

                    $contador++;
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
    }
}
