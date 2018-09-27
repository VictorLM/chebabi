<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

    protected $data;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->data = Carbon::parse(Carbon::now())->format('d-m-Y');

        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s | gzip > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('backups/backup_intranet_'.$this->data.'.gz')
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
            Mail::send('emails.backup', ['content' => $this->data], 
            function ($message)
            {
                $message->from('intranet@chebabi.adv.br', 'Intranet Chebabi');
                $message->to('victor@chebabi.com', null);
                $message->subject('Backup Intranet '.$this->data);
                $message->attach(storage_path('backups/backup_intranet_'.$this->data.'.gz'));
            });
            //APAGA O BACKUP DO SERVIDOR DEPOIS DE ENVIAR POE E-MAIL
            Storage::disk('local')->delete('/backups/backup_intranet_'.$this->data.'.gz');
        }
    }
}
