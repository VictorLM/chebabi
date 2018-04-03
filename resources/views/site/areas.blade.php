@extends ('site.templates.template')

@section('conteudo')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light breadcrumb-custom">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Áreas</li>
        </ol>
    </nav>

    <div class="card bg-light mb-3">
        <div class="card-body">

            <h1 class="text-center">ÁREAS DE ATUAÇÃO</h1>
            <hr/>

            <div class="row row-eq-height">

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-briefcase"></i> ADMINISTRATIVO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; An&aacute;lise  de editais, defesas administrativas, licita&ccedil;&otilde;es e concorr&ecirc;ncias p&uacute;blicas,  contratos p&uacute;blicos, 
                                permiss&atilde;o p&uacute;blica; PPP, orienta&ccedil;&otilde;es jur&iacute;dicas sobre normas  expedidas pelas ag&ecirc;ncias reguladoras 
                                (Telecomunica&ccedil;&otilde;es, Energia,  Petr&oacute;leo).
                            </p>
                        </div>

                    </div>
                </div>
        
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-recycle"></i> AMBIENTAL
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Orienta&ccedil;&otilde;es sobre pedidos de  licen&ccedil;as e estudos de impacto ambiental, defesas administrativas e judiciais  relativas a multas 
                                (CETESB, ANP e outros &oacute;rg&atilde;os), orienta&ccedil;&otilde;es sobre preven&ccedil;&atilde;o  &agrave; pr&aacute;tica de crimes ambientais, 
                                defesa em a&ccedil;&atilde;o civil p&uacute;blica.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-university"></i> BANCÁRIO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Cobran&ccedil;a,  monit&oacute;ria, execu&ccedil;&otilde;es, a&ccedil;&otilde;es de busca e apreens&atilde;o, a&ccedil;&otilde;es 
                                de reintegra&ccedil;&atilde;o de  posse, embargos de terceiro, protesto pela prefer&ecirc;ncia, habilita&ccedil;&atilde;o em  fal&ecirc;ncia, 
                                a&ccedil;&otilde;es revisionais de contrato e indenizat&oacute;rias. C&eacute;dula de cr&eacute;dito  banc&aacute;rio, comercial; rural, industrial. 
                                Securitiza&ccedil;&atilde;o e alongamento de d&iacute;vidas.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-money"></i> COMERCIAL
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Elabora&ccedil;&atilde;o,  revis&atilde;o e defesa judicial lastreada contratos comerciais, atendimento a  empresas nacionais e estrangeiras, 
                                que buscam adequar seus contratos  sociais&nbsp; &agrave;s leis brasileiras,  orienta&ccedil;&otilde;es sobre legisla&ccedil;&atilde;o falimentar; 
                                legisla&ccedil;&atilde;o de t&iacute;tulos de cr&eacute;dito  (cheques, duplicatas, nota promiss&oacute;ria, c&eacute;dulas e notas de cr&eacute;dito &ndash;  
                                industrial, comercial e rural, deb&ecirc;ntures) e legisla&ccedil;&atilde;o de marcas e patentes, aquisi&ccedil;&atilde;o, fus&atilde;o de sociedades.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-shopping-cart"></i> CONSUMIDOR
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Procedimentos  administrativos em PROCON, aconselhamento a fornecedores e consumidores, Defesas em A&ccedil;&atilde;o Civil P&uacute;blica, 
                                habilita&ccedil;&atilde;o em a&ccedil;&otilde;es coletivas, elabora&ccedil;&atilde;o de  contratos. Elabora&ccedil;&atilde;o de regras de campanhas 
                                de marketing e obten&ccedil;&atilde;o de  aprova&ccedil;&atilde;o de sorteios perante a Caixa Econ&ocirc;mica Federal.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-file-text"></i> CONTRATOS
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Elabora&ccedil;&atilde;o de  contratos; aditivos, rescis&atilde;o contratual; terceiriza&ccedil;&otilde;es; franquias;  
                                distribui&ccedil;&atilde;o, ag&ecirc;ncia e representa&ccedil;&atilde;o comercial; presta&ccedil;&atilde;o de servi&ccedil;os; 
                                licenciamento  de propriedade intelectual; comiss&atilde;o mercantil; garantias reais e  fidejuss&oacute;rias.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-users"></i> FAM&Iacute;LIA E SUCESS&Otilde;ES
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Planejamento  sucess&oacute;rio; div&oacute;rcio; alimentos; guarda de filhos; 
                                investiga&ccedil;&atilde;o de paternidade;  testamentos; invent&aacute;rios 
                                e arrolamentos; aconselhamento sobre regime de bens;  altera&ccedil;&atilde;o de 
                                regime de bens; interdi&ccedil;&atilde;o; ado&ccedil;&atilde;o.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-home"></i> IMOBILI&Aacute;RIO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Rela&ccedil;&otilde;es  locat&iacute;cias comerciais, residenciais; Shopping Center; indeniza&ccedil;&otilde;es;  
                                incorpora&ccedil;&otilde;es imobili&aacute;rias; institui&ccedil;&atilde;o e extin&ccedil;&atilde;o de condom&iacute;nio; 
                                loteamento;  cooperativas habitacionais; compra e venda; permuta; da&ccedil;&atilde;o em pagamento.  usucapi&atilde;o; 
                                desapropria&ccedil;&atilde;o; demarcat&oacute;rias; possess&oacute;rias. Estrutura&ccedil;&atilde;o de  
                                neg&oacute;cios imobili&aacute;rios. Retifica&ccedil;&atilde;o de &aacute;rea.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-gavel"></i> PREVIDENCI&Aacute;RIO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Defesa  dos interesses de clientes em processos administrativos e demandas judiciais, revis&atilde;o  e requerimentos de benef&iacute;cio, 
                                indeniza&ccedil;&otilde;es por acidente de trabalho, demandas  judiciais para recebimento de benef&iacute;cio.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-umbrella"></i> SECURIT&Aacute;RIO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Atua&ccedil;&atilde;o no &acirc;mbito consultivo e contencioso, judicial  e administrativo, a Seguradoras, 
                                Corretores e Agentes, em todos os ramos de  seguro, ressarcimentos administrativos e judiciais, an&aacute;lise e 
                                elabora&ccedil;&atilde;o de  condi&ccedil;&otilde;es de ap&oacute;lices de seguros e planos de benef&iacute;cios, 
                                gerenciamento de  sinistros.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-handshake-o"></i> SOCIET&Aacute;RIO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Constitui&ccedil;&atilde;o e  dissolu&ccedil;&atilde;o de sociedades; defesa dos interesses dos s&oacute;cios, majorit&aacute;rios ou  minorit&aacute;rios; 
                                fal&ecirc;ncia e recupera&ccedil;&atilde;o judicial; elabora&ccedil;&atilde;o de estatutos  sociais; contratos sociais; atas de assembleias gerais; 
                                acordos de acionistas;  elabora&ccedil;&atilde;o de estudos e pareceres sobre assuntos societ&aacute;rios em geral, a&ccedil;&atilde;o de  exclus&atilde;o 
                                de s&oacute;cio, a&ccedil;&atilde;o de dissolu&ccedil;&atilde;o de sociedade; fus&atilde;o; incorpora&ccedil;&atilde;o e  reestrutura&ccedil;&atilde;o de empresas.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-globe"></i> TERCEIRO SETOR
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Negocia&ccedil;&atilde;o de  conv&ecirc;nios e parcerias com entidades p&uacute;blicas e n&atilde;o-governamentais; assessoria e  constitui&ccedil;&atilde;o 
                                de ONG, Associa&ccedil;&otilde;es, aconselhamento sobre a Lei Roaunet e Lei do  Esporte.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-balance-scale"></i> TRABALHISTA
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Defesas e  proposituras de a&ccedil;&otilde;es individuais e coletivas; mandados de seguran&ccedil;a, a&ccedil;&atilde;o  
                                rescis&oacute;ria, sustenta&ccedil;&atilde;o oral; consultoria &agrave;s &aacute;reas de recursos humanos, departamento pessoal e 
                                jur&iacute;dico, elabora&ccedil;&atilde;o de pareceres; defesa de s&oacute;cios,  ex-s&oacute;cios e terceiros em execu&ccedil;&otilde;es 
                                trabalhistas; <em>due diligence</em> para avaliar riscos trabalhistas.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 text-justify display-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                <i class="fa fa-calculator"></i> TRIBUT&Aacute;RIO
                            </h3>
                        </div>
        
                        <div class="card-body">
                            <p class="card-text">
                                &emsp; Assessoria visando a ado&ccedil;&atilde;o de  pr&aacute;ticas comerciais, operacionais e financeiras objetivando reduzir licitamente  a carga 
                                tribut&aacute;ria da empresa, planejamento tribut&aacute;rio, contencioso judicial  e administrativo, recupera&ccedil;&atilde;o de ativos fiscais, 
                                orienta&ccedil;&otilde;es sobre crimes  tribut&aacute;rios, defesa em execu&ccedil;&otilde;es fiscais.
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
