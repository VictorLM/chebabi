@extends ('site.templates.template')

@section('conteudo')
<div id="conteudo">
    <!-- Notícias -->
    <h1>
        <a id="noticias-link" href="https://www.aasp.org.br/noticias/" target="_blank">
            NOTÍCIAS AASP
        </a>
    </h1>
    <div id="noticias">

        <div class="noticia">
            @if(!empty($noticias))
                @foreach ($noticias->channel->item as $noticia)
                    <p class="pnoticia">
                        {{Carbon\Carbon::parse($noticia->pubDate)->format('d/m/Y')}} - 
                        <a href="{{$noticia->link}}" target="_blank">{{$noticia->title}}</a>
                    </p>
                @endforeach
            @else
                <p>Não foi possível acessar as notícias.</p>
            @endif
        </div>

    </div>
    <!-- Fim Notícias -->
</div>

@endsection