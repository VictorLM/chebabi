<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class EmailAniversarioTodos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EmailAniversarioTodos:enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checa os aniversariantes do dia e envia um e-mail de aviso p/ todos avisando (Qual a necessidade disso sendo que já há a notificação no Skype e na Intranet?).';

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

        if(!empty($aniversariantes) && !$aniversariantes->isEmpty()){

            $aniversariantes_ids = $aniversariantes->pluck('id');

            $users = DB::table('users')
                ->select('id','name','email')
                ->where('ativo', TRUE)
                ->whereNotIn('id', $aniversariantes_ids)
                ->get();
            
            foreach($users as $user){
                Mail::send('emails.aniversario_todos', ['content' => $aniversariantes], function ($message) use ($aniversariantes, $user)
                {
                    $message->from('chebabi@chebabi.adv.br', 'Chebabi - Izique Chebabi Advogados');
                    $message->to($user->email, $user->name = null);
                    $message->subject('Hoje é o aniversário de ...');
                });
            }
        }
    }
}
