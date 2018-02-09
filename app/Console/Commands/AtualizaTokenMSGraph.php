<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AtualizaTokenMSGraph extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AtualizaTokenMSGraph:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o token de acesso da API Microsoft Graph. TTL de uma hora.';

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
        $credentials = DB::table('apikeys')
            ->select('key', 'secret')
            ->where('name', 'Microsoft Graph')
            ->first();
        
        $keyid = $credentials->key;
        $secret = $credentials->secret;
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://login.microsoftonline.com/chebabi.com/oauth2/v2.0/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id='.$keyid.'&scope=https%3A%2F%2Fgraph.microsoft.com%2F.default&client_secret='.$secret.'&grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        $token = json_decode($result,true);

        DB::table('apikeys')
            ->where('name', 'Microsoft Graph')
            ->update(['token' => $token["access_token"],
                        'updated_at' => Carbon::now()]);

        //return ("TOKEN ATUALIZADO ". Carbon::now()); 
    }
}
