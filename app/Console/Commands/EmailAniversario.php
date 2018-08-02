<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class EmailAniversario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EmailAniversario:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checa os aniversariantes do dia e envia um e-mail parabenizando-os.';

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
        $aniversariantes = DB::table('users')
            ->select('id','name','email')
            ->where('ativo', TRUE)
            ->whereMonth('nascimento', Carbon::today()->month)
            ->whereDay('nascimento', Carbon::today()->day)
            ->get();

        foreach($aniversariantes as $aniversariante){
            Mail::send('emails.aniversario', ['content' => $aniversariante->name], function ($message) use ($aniversariante)
            {
                $message->from('chebabi@chebabi.adv.br', 'Chebabi - Izique Chebabi Advogados');
                $message->to($aniversariante->email, $aniversariante->name = null);
                $message->subject('Parabéns!');
            });
        }
    }
}
