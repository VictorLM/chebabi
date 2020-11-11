<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        $dics = [
            'curriculos', 
            'intranet/pdf/relatorios', 
            'intranet/pdf/relatorios/cliente', 
            'intranet/pdf/comprovantes', 
            'intranet/pdf/correios/relatorios',
            'intranet/pdf/correios/anexos',
        ];
        // RIP TRY CATCH
        foreach($dics as $dic){

            $files = Storage::files($dic);
            if(count($files) > 0) {
                Storage::delete($files);
            }

        }
        
    }
}
