<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AtualizaTokenLegalOne extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AtualizaTokenLegalOne:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o token de acesso a API do LegalOne. TTL de 30 minutos.';

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
        //TTL 30 MINUTOS
        $credentials = DB::table('apikeys')
            ->select('key', 'secret')
            ->where('name', 'Legal One Corporate Brazil')
            ->first();
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.thomsonreuters.com/legalone/oauth/oauth?grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_USERPWD, $credentials->key . ":" . $credentials->secret);

        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        $token = json_decode($result);

        DB::table('apikeys')
            ->where('name', 'Legal One Corporate Brazil')
            ->update(['token' => $token->access_token,
                        'updated_at' => Carbon::now()]);

        //return ("TOKEN ATUALIZADO ". Carbon::now()); 
    }
}
