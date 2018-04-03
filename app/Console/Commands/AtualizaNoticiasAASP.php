<?php

namespace Intranet\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Intranet\Blog_Noticias;

class AtualizaNoticiasAASP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AtualizaNoticiasAASP:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza as notícias do site da AASP com o BD local para exibição no blog';

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
        $feed = 'https://www.aasp.org.br/feed/?post_type=noticias';
        $noticias = json_decode(json_encode((array)simplexml_load_file($feed)),1);

        foreach($noticias['channel']['item'] as $noticia){
            Blog_Noticias::updateOrCreate(
                ['guid' => $noticia['guid']],
                [
                    'guid' => $noticia['guid'],
                    'titulo' => $noticia['title'],
                    'link' => $noticia['link'],
                    'publicacao' => Carbon::parse($noticia['pubDate'])->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::now(),
                ]
            );
        }

    }
}
