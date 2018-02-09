<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AtualizaTiposAndamentosLegalOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AtualizaTiposAndamentosLegalOne:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os tipos de andamentos cadastrados no Legal One.';

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
    public function handle()
    {
        $token = DB::table('apikeys')->where('name', 'Legal One Corporate Brazil')->value('token');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.thomsonreuters.com/legalone/v1/api/rest/UpdateAppointmentTaskTypes?$filter=isProgressType eq true');
        $headers = array('Authorization: Bearer ' . $token);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $result = json_decode($result, TRUE);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tipos_andamentos_legalone')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        

        if(!empty($result)){
            
            foreach($result['value'] as $tipo){
                DB::table('tipos_andamentos_legalone')->insert(
                    ['id' => $tipo['id'], 
                    'name' => $tipo['name'], 
                    'updated_at' => Carbon::now()]
                );
            }

        }

    }
}
