<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup do DB e envio por e-mail do arquivo comprimido.';

    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s | gzip > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('backups/backup_intranet_'.Carbon::parse(Carbon::now())->format('d-m-Y').'.gz')
        ));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $this->process->mustRun();
            //$this->info('Backup efetudo com sucesso.');
        }catch(ProcessFailedException $exception){
            $this->error('Erro ao executar o backup: '.$exception);
        }

        if($this->process->isSuccessful()){
            //ZIPA?
            //E-MAIL
            //EXCLUI O ARQUIVO
        }
    }
}
