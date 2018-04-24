@extends ('site.templates.template')

@section('conteudo')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Equipe</li>
            <li class="breadcrumb-item active" aria-current="page">Advogados</li>
        </ol>
    </nav>

    <div class="card bg-light mb-3">
        <div class="card-body">

            <h1 class="text-center">ADVOGADOS</h1>
            <hr/>
            <div class="row row-eq-height">

                @foreach ($equipe as $adv)

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 display-grid">

                        <div class="card">
                            <div class="card-body text-center">
                                <a class="advs-link" data-toggle="modal" data-target="#{{$adv->id}}" href="#">
                                    <img class="img-fluid rounded advs-img" src="{{$adv->foto}}" alt="advogados">
                                    <hr/>
                                    <h5 class="card-title">{{mb_strtoupper($adv->nome_usuario->name, 'UTF-8')}}</h5>
                                    <p>OAB {{mb_strtoupper($adv->oab, 'UTF-8')}} <span class="badge badge-pill badge-primary">{{mb_strtoupper($adv->tipo_adv, 'UTF-8')}}</span></p>
                                </a>
                            </div>
                        </div>
    
                        <!-- Modal -->
                        <div class="modal fade" id="{{$adv->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <img class="img-fluid rounded advs-img-modal" src="{{$adv->foto}}" alt="advogados">
                                        <div class="texto-adv">
                                            <h5 class="modal-title"><b>{{mb_strtoupper($adv->nome_usuario->name, 'UTF-8')}}</b></h5>
                                            <ul class="advs-modal">
                                                <li><span class="badge badge-pill badge-primary">{{mb_strtoupper($adv->tipo_adv, 'UTF-8')}}</span></li>
                                                <li><i class="fa fa-address-card"></i> OAB {{mb_strtoupper($adv->oab, 'UTF-8')}}</li>
                                                <li><i class="fa fa-envelope"></i> <a href="mailto:{{$adv->nome_usuario->email}}">{{$adv->nome_usuario->email}}</a></li>
                                                <li><i class="fa fa-phone-square"></i> (19) 3237-3747 Ramal {{$adv->nome_usuario->ramal}}</li>
                                            </ul>
                                        </div>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-justify">&emsp; {{$adv->texto}}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>

                @endforeach

                
            </div>

        </div>
    </div>

@endsection
