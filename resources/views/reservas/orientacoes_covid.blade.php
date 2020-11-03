<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title ?? "Intranet Chebabi"}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .bg-azul{
            background-color: #44546a; 
        } 
        .azul {
            color: #44546a; 
        }
    </style>
</head>
<body class="bg-secondary">
    <br/>
    <div class="container bg-light rounded">

        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
            <br/>
                <div class="alert alert-{{$msg}}">
                    {!! Session::get('alert-' . $msg) !!}
                </div>
            @endif
        @endforeach
        
        <div class="row">
            <div class="col-lg-1 d-none d-lg-block m-auto">
                <i class="fas fa-virus-slash fa-3x"></i>
            </div>
            <div class="col-lg-10 m-auto">
                <h2 class='text-center'>ORIENTAÇÕES DE DISTANCIAMENTO SOCIAL E HIGIENE PARA PREVENÇÃO DE CONTÁGIO DA COVID-19</h2>
            </div>
            <div class="col-lg-1 d-none d-lg-block m-auto">
                <i class="fas fa-shield-virus fa-3x"></i>
            </div>
        </div>
        <hr/>
        <div class="row">
            
            <div class="col-lg-4 m-auto">
                <div class="row">
                    <div class="col-md-3 m-auto">
                        <i class="fas fa-people-arrows fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-9 px-4">
                        <p class="text-justify font-weight-bold azul">
                            UTILIZAR AS ESTAÇÕES DE TRABALHO DE FORMA INTERCALADA RESPEITANDO AS MARCAÇÕES 
                            E MANTENDO SEMPRE UMA DISTÂNCIA MINIMA DE 1,5 M DA OUTRA PESSOA.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 m-auto">
                <div class="row">
                    <div class="col-md-3 m-auto">
                        <i class="fas fa-head-side-mask fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-9 px-4">
                        <p class="text-justify font-weight-bold azul">
                            OBRIGATÓRIO A UTILIZAÇÃO DE MÁSCARA DURANTE TODO O PERÍODO QUE ESTIVER NO ESCRITÓRIO. 
                            TROCANDO A MESMA SEMPRE QUE NECESSÁRIO E REALIZANDO O DESCARTE DE FORMA CORRETA. 

                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 m-auto">
                <div class="row">
                    <div class="col-md-3 m-auto">
                        <i class="fas fa-hand-holding-water fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-9 px-4">
                        <p class="text-justify font-weight-bold azul">
                            HIGIENIZAR AS MÃOS COM ALCOOL EM GEL COM FREQUÊNCIA E SEMPRE QUE TOCAR EM SUPERFÍCIES QUE 
                            SÃO TOCADAS POR OUTRAS PESSOAS.
                        </p>
                    </div>
                </div>
            </div>
    
        </div>

        <div class="row">
            
            <div class="col-lg-3 m-auto">
                <div class="row">
                    <div class="col-md-4 m-auto">
                        <i class="fas fa-handshake-slash fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-8 px-4">
                        <p class="text-justify font-weight-bold azul">
                            NÃO COMPARTILHE OBJETOS DE USO PESSOAL.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 m-auto">
                <div class="row">
                    <div class="col-md-4 m-auto">
                        <i class="fas fa-soap fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-8 px-4">
                        <p class="text-justify font-weight-bold azul">
                            HIGIENIZAR TECLADO, MOUSE E OBJETOS PESSOAIS ANTES DE UTILIZAR. 
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 m-auto">
                <div class="row">
                    <div class="col-md-4 m-auto">
                        <i class="fas fa-hands-wash fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-8 px-4">
                        <p class="text-justify font-weight-bold azul">
                            NÃO TOQUE EM SEU ROSTO, OLHOS E NARIZ SEM HIGIENIZAR AS MÃOS.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 m-auto">
                <div class="row">
                    <div class="col-md-4 m-auto">
                        <i class="fas fa-toilet-paper fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-8 px-4">
                        <p class="text-justify font-weight-bold azul">
                            HIGIENIZAR O VASO SANITÁRIO COM ÁLCOOL EM GEL ANTES E APÓS A UTILIZAÇÃO. 
                        </p>
                    </div>
                </div>
            </div>
    
        </div>
        <br/>
        <div class="row">
            
            <div class="col-lg-4 m-auto">
                <div class="row">
                    <div class="col-md-3 m-auto">
                        <i class="fas fa-fan fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-9 px-4">
                        <p class="text-justify font-weight-bold azul">
                            MANTER O AMBIENTE BEM VENTILADO DEIXANDO AS JANELAS ABERTAS AO INVES DA UTILIZAR O AR-CONDICIONADO.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 m-auto">
                <div class="row">
                    <div class="col-md-3 m-auto">
                        <i class="fas fa-head-side-cough fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-9 px-4">
                        <p class="text-justify font-weight-bold azul">
                            APRESENTANDO QUALQUER SINTOMA COMUNIQUE SUA COORDENADORA E FIQUE EM CASA!
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 m-auto">
                <div class="row">
                    <div class="col-md-3 m-auto">
                        <i class="fas fa-laptop-house fa-3x fa-border rounded-circle bg-azul text-white p-3"></i>
                    </div>
                    <div class="col-md-9 px-4">
                        <p class="text-justify font-weight-bold azul">
                            RESPEITE A ESCALA DE HOME OFFICE E NÃO COMPARECA AO ESCRITÓRIO SEM PRÉVIO ALINHAMENTO COM SUA COORDENADORA.
                        </p>
                    </div>
                </div>
            </div>
    
        </div>
        <hr/>
        <h3><a href="{{url('/intranet')}}"><i class="fas fa-arrow-left"></i> Voltar para Intranet</a></h3>
        <br/>
    </div>
    <br/>

</body>
</html>
