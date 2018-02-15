<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Intranet\Eventos;

class AtualizaEventosAgenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AtualizaEventosAgenda:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os eventos da agenda. Pega só os eventos até três meses a partir da data atual';

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
    
    private function cURL($parameters){
        //REQUER VPS PARA ALTERAR O LIMITE NO PHP.
        set_time_limit(600);
        
        $token = DB::table('apikeys')
            ->select('token')
            ->where('name', 'Microsoft Graph')
            ->first();
         
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Authorization: Bearer ".$token->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        
        $result = json_decode($result,true);
        
        $result = (array) $result;
        
        return $result;

    }
    
    public function handle()
    {
        //EM MÉDIA 3 MINUTOS PARA EXECUTAR
        //OS EVENTOS JÁ SÃO CRIADO NA INTRA E INSERIDOS NO BD
        //FUNÇÃO APENAS PARA SYNCAR EVENTOS FEITOS PELO OUTLOOK
        //DE QUEM ESTÁ NA CAIXA AGENDA@CHEBABI.COM

        $parameters = 'https://graph.microsoft.com/v1.0/users/agenda@chebabi.com/'
                .'calendarview?startdatetime='.Carbon::parse(Carbon::today())->format('Y-m-d')
                .'&enddatetime='
                .Carbon::parse(Carbon::today()->addMonths(3))->format('Y-m-d');
                //SYNCA EVENTOS DA DATA ATUAL ATÉ 3 MESES NO FUTURO
        
        $result = $this->cURL($parameters);
        //dd($result);
        $contador = 0;
        $next = TRUE;
        do {
            if(!isset($result["error"]) && !empty($result["value"])){
                foreach ($result['value'] as $key => $value) {
                    $i = 0;
                    $envolvido_nome0 = "";
                    $envolvido_nome1 = "";
                    $envolvido_nome2 = "";
                    $envolvido_nome3 = "";
                    $envolvido_email0 = "";
                    $envolvido_email1 = "";
                    $envolvido_email2 = "";
                    $envolvido_email3 = "";
                    while($i < 4){
                        if(isset($value['attendees'][$i])){
                            ${"envolvido_nome".$i} = $value['attendees'][$i]['emailAddress']['name'];
                            ${"envolvido_email".$i}  = $value['attendees'][$i]['emailAddress']['address'];
                        }
                        $i++;
                    }

                    $tipo = strtok($value['subject'], ' ');

                    //QUANDO O EVENTO É CANCELADO ELE É EXCLUÍDO E NÃO VEM MAIS NA QUERY
                    //FAZER OUTRA ROTINA QUE ATUALIZE OS EVENTOS EXISTENTES

                    if($value['isCancelled']){
                        $color = 'red';
                    }else if($tipo == '[Ausente]'){
                        $color = '#E6EB0A';
                    }else if($tipo == '[Carro]'){
                        $color = '#00e600';
                    }else if($tipo == '[Motorista]'){
                        $color = '#ffa31a';
                    }else if($tipo == '[Reunião]'){
                        $color = '#80bfff';
                    }else{
                        $color = NULL;
                    }

                    $tipo = str_replace("[", "", $tipo);
                    $tipo = str_replace("]", "", $tipo);

                    if($tipo != 'Ausente' || $tipo != 'Carro' || $tipo != 'Motorista' || $tipo != 'Reunião' || $tipo != 'Outro'){
                        $tipo = NULL;
                    }

                    Eventos::updateOrCreate(
                        ['id' => $value['id']],
                        [
                            'id' => $value['id'],
                            'title' => $value['subject'],
                            'tipo' => $tipo,
                            'start' => Carbon::parse($value['start']['dateTime'])->subHours(2)->format('Y-m-d H:i:s'),
                            'end' => Carbon::parse($value['end']['dateTime'])->subHours(2)->format('Y-m-d H:i:s'),
                            'descricao' => $value['body']['content'],
                            'created_at' => Carbon::parse($value['createdDateTime'])->subHours(2)->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::parse($value['lastModifiedDateTime'])->subHours(2)->format('Y-m-d H:i:s'),
                            'organizador_nome' => $value['organizer']['emailAddress']['name'],
                            'organizador_email' => $value['organizer']['emailAddress']['address'],
                            'url' => "/intranet/agenda/evento/".$value['id'],
                            'allDay' => $value['isAllDay'],
                            'cancelado' => $value['isCancelled'],
                            'local' => $value['location']['displayName'],
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
                        ]
                    );
                    $contador++;
                }
                if(isset($result["@odata.nextLink"])){
                    $result = $this->cURL($result["@odata.nextLink"]);
                    $next = TRUE;
                }else{
                    $next = FALSE;
                }
            }else{
                $next = FALSE;
            }
        }while($next);
        //return ($contador." EVENTOS PROCESSADOS.");  
    }
}
