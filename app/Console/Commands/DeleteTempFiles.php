<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DeleteTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TempFiles:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleta arquivos PDF temporarios. Currículos, Relatórios de viagens, Comprovantes, Correios, etc.';

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
        $file = new Filesystem;
        // RIP TRY CATCH
        $file->cleanDirectory(storage_path('app/curriculos'));
        $file->cleanDirectory(storage_path('app/intranet/pdf/relatorios'));
        $file->cleanDirectory(storage_path('app/intranet/pdf/correios/relatorios'));
        $file->cleanDirectory(storage_path('app/intranet/pdf/correios/anexos'));
        $file->cleanDirectory(storage_path('app/intranet/pdf/comprovantes'));
        // Recriar dic deletado p/ relatórios de viagens dos clientes
        $file->makeDirectory(storage_path('app/intranet/pdf/relatorios/cliente'));
    }
}
