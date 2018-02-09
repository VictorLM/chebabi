@extends ('site.templates.template')

@section('conteudo')

<div id="conteudo">
    <!-- Areas -->
    <div id="areas">
        <h1>ÁREAS DE ATUAÇÃO</h1>

        <div class="area">
            <a href="#modalTrabalhista"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/trabalhista.png')}}"><h3>TRABALHISTA</h3></a>
                <div id="modalTrabalhista" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/trabalhista.png')}}"></div>
                        <h2>TRABALHISTA</h2>
                        <p>Defesas e  proposituras de a&ccedil;&otilde;es individuais e coletivas; mandados de seguran&ccedil;a, a&ccedil;&atilde;o  
                        rescis&oacute;ria, sustenta&ccedil;&atilde;o oral; consultoria &agrave;s &aacute;reas de recursos humanos, departamento pessoal e 
                        jur&iacute;dico, elabora&ccedil;&atilde;o de pareceres; defesa de s&oacute;cios,  ex-s&oacute;cios e terceiros em execu&ccedil;&otilde;es 
                        trabalhistas; <em>due diligence</em> para avaliar riscos trabalhistas.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalImobiliario"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/imobiliario.png')}}"><h3>IMOBILI&Aacute;RIO</h3></a>
                <div id="modalImobiliario" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/imobiliario.png')}}"></div>
                        <h2>IMOBILI&Aacute;RIO</h2>
                        <p>Rela&ccedil;&otilde;es  locat&iacute;cias comerciais, residenciais; Shopping Center; indeniza&ccedil;&otilde;es;  
                        incorpora&ccedil;&otilde;es imobili&aacute;rias; institui&ccedil;&atilde;o e extin&ccedil;&atilde;o de condom&iacute;nio; 
                        loteamento;  cooperativas habitacionais; compra e venda; permuta; da&ccedil;&atilde;o em pagamento.  usucapi&atilde;o; 
                        desapropria&ccedil;&atilde;o; demarcat&oacute;rias; possess&oacute;rias. Estrutura&ccedil;&atilde;o de  
                        neg&oacute;cios imobili&aacute;rios. Retifica&ccedil;&atilde;o de &aacute;rea.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalContratos"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/contratos.png')}}"><h3>CONTRATOS</h3></a>
                <div id="modalContratos" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/contratos.png')}}"></div>
                        <h2>CONTRATOS</h2>
                        <p>Elabora&ccedil;&atilde;o de  contratos; aditivos, rescis&atilde;o contratual; terceiriza&ccedil;&otilde;es; franquias;  
                        distribui&ccedil;&atilde;o, ag&ecirc;ncia e representa&ccedil;&atilde;o comercial; presta&ccedil;&atilde;o de servi&ccedil;os; 
                        licenciamento  de propriedade intelectual; comiss&atilde;o mercantil; garantias reais e  fidejuss&oacute;rias.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalBancario"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/bancario.png')}}"><h3>BANC&Aacute;RIO</h3></a>
                <div id="modalBancario" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/bancario.png')}}"></div>
                        <h2>BANC&Aacute;RIO</h2>
                        <p>Cobran&ccedil;a,  monit&oacute;ria, execu&ccedil;&otilde;es, a&ccedil;&otilde;es de busca e apreens&atilde;o, a&ccedil;&otilde;es 
                        de reintegra&ccedil;&atilde;o de  posse, embargos de terceiro, protesto pela prefer&ecirc;ncia, habilita&ccedil;&atilde;o em  fal&ecirc;ncia, 
                        a&ccedil;&otilde;es revisionais de contrato e indenizat&oacute;rias. C&eacute;dula de cr&eacute;dito  banc&aacute;rio, comercial; rural, industrial. 
                        Securitiza&ccedil;&atilde;o e alongamento de d&iacute;vidas.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalSocietario"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/societario.png')}}"><h3>SOCIET&Aacute;RIO</h3></a>
                <div id="modalSocietario" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/societario.png')}}"></div>
                        <h2>SOCIET&Aacute;RIO</h2>
                        <p>Constitui&ccedil;&atilde;o e  dissolu&ccedil;&atilde;o de sociedades; defesa dos interesses dos s&oacute;cios, majorit&aacute;rios ou  minorit&aacute;rios; 
                        fal&ecirc;ncia e recupera&ccedil;&atilde;o judicial; elabora&ccedil;&atilde;o de estatutos  sociais; contratos sociais; atas de assembleias gerais; 
                        acordos de acionistas;  elabora&ccedil;&atilde;o de estudos e pareceres sobre assuntos societ&aacute;rios em geral, a&ccedil;&atilde;o de  exclus&atilde;o 
                        de s&oacute;cio, a&ccedil;&atilde;o de dissolu&ccedil;&atilde;o de sociedade; fus&atilde;o; incorpora&ccedil;&atilde;o e  reestrutura&ccedil;&atilde;o de empresas.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalComercial"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/comercial.png')}}"><h3>COMERCIAL</h3></a>
                <div id="modalComercial" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/comercial.png')}}"></div>
                        <h2>COMERCIAL</h2>
                        <p>Elabora&ccedil;&atilde;o,  revis&atilde;o e defesa judicial lastreada contratos comerciais, atendimento a  empresas nacionais e estrangeiras, 
                        que buscam adequar seus contratos  sociais&nbsp; &agrave;s leis brasileiras,  orienta&ccedil;&otilde;es sobre legisla&ccedil;&atilde;o falimentar; 
                        legisla&ccedil;&atilde;o de t&iacute;tulos de cr&eacute;dito  (cheques, duplicatas, nota promiss&oacute;ria, c&eacute;dulas e notas de cr&eacute;dito &ndash;  
                        industrial, comercial e rural, deb&ecirc;ntures) e legisla&ccedil;&atilde;o de marcas e patentes, aquisi&ccedil;&atilde;o, fus&atilde;o de sociedades.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalFamilia"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/familia.png')}}"><h3>FAM&Iacute;LIA E SUCESS&Otilde;ES</h3></a>
                <div id="modalFamilia" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/familia.png')}}"></div>
                        <h2>FAM&Iacute;LIA E SUCESS&Otilde;ES</h2>
                        <p>Planejamento  sucess&oacute;rio; div&oacute;rcio; alimentos; guarda de filhos; investiga&ccedil;&atilde;o de paternidade;  testamentos; invent&aacute;rios 
                        e arrolamentos; aconselhamento sobre regime de bens;  altera&ccedil;&atilde;o de regime de bens; interdi&ccedil;&atilde;o; ado&ccedil;&atilde;o.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalSecuritario"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/securitario.png')}}"><h3>SECURIT&Aacute;RIO</h3></a>
                <div id="modalSecuritario" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/securitario.png')}}"></div>
                        <h2>SECURIT&Aacute;RIO</h2>
                        <p>Atua&ccedil;&atilde;o no &acirc;mbito consultivo e contencioso, judicial  e administrativo, a Seguradoras, Corretores e Agentes, em todos os ramos de  seguro, 
                        ressarcimentos administrativos e judiciais, an&aacute;lise e elabora&ccedil;&atilde;o de  condi&ccedil;&otilde;es de ap&oacute;lices de seguros e planos 
                        de benef&iacute;cios, gerenciamento de  sinistros.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalTributario"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/tributario.png')}}"><h3>TRIBUT&Aacute;RIO</h3></a>
                <div id="modalTributario" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/tributario.png')}}"></div>
                        <h2>TRIBUT&Aacute;RIO</h2>
                        <p>Assessoria visando a ado&ccedil;&atilde;o de  pr&aacute;ticas comerciais, operacionais e financeiras objetivando reduzir licitamente  a carga 
                        tribut&aacute;ria da empresa, planejamento tribut&aacute;rio, contencioso judicial  e administrativo, recupera&ccedil;&atilde;o de ativos fiscais, 
                        orienta&ccedil;&otilde;es sobre crimes  tribut&aacute;rios, defesa em execu&ccedil;&otilde;es fiscais.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalPrevidenciario"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/previdenciario.png')}}"><h3>PREVIDENCI&Aacute;RIO</h3></a>
                <div id="modalPrevidenciario" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/previdenciario.png')}}"></div>
                        <h2>PREVIDENCI&Aacute;RIO</h2>
                        <p>Defesa  dos interesses de clientes em processos administrativos e demandas judiciai, revis&atilde;o  e requerimentos de benef&iacute;cio, 
                        indeniza&ccedil;&otilde;es por acidente de trabalho, demandas  judiciais para recebimento de benef&iacute;cio.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalAdm"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/administrativo.png')}}"><h3>ADMINISTRATIVO</h3></a>
                <div id="modalAdm" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/administrativo.png')}}"></div>
                        <h2>ADMINISTRATIVO</h2>
                        <p>An&aacute;lise  de editais, defesas administrativas, licita&ccedil;&otilde;es e concorr&ecirc;ncias p&uacute;blicas,  contratos p&uacute;blicos, 
                        permiss&atilde;o p&uacute;blica; PPP, orienta&ccedil;&otilde;es jur&iacute;dicas sobre normas  expedidas pelas ag&ecirc;ncias reguladoras 
                        (Telecomunica&ccedil;&otilde;es, Energia,  Petr&oacute;leo).</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalAmbiental"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/ambiental.png')}}"><h3>AMBIENTAL</h3></a>
                <div id="modalAmbiental" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/ambiental.png')}}"></div>
                        <h2>AMBIENTAL</h2>
                        <p>Orienta&ccedil;&otilde;es sobre pedidos de  licen&ccedil;as e estudos de impacto ambiental, defesas administrativas e judiciais  relativas a multas 
                        (CETESB, ANP e outros &oacute;rg&atilde;os), orienta&ccedil;&otilde;es sobre preven&ccedil;&atilde;o  &agrave; pr&aacute;tica de crimes ambientais, 
                        defesa em a&ccedil;&atilde;o civil p&uacute;blica.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalConsumidor"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/consumidor.png')}}"><h3>CONSUMIDOR</h3></a>
                <div id="modalConsumidor" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/consumidor.png')}}"></div>
                        <h2>CONSUMIDOR</h2>
                        <p>Procedimentos  administrativos em PROCON, aconselhamento a fornecedores e consumidores, Defesas em A&ccedil;&atilde;o Civil P&uacute;blica, 
                        habilita&ccedil;&atilde;o em a&ccedil;&otilde;es coletivas, elabora&ccedil;&atilde;o de  contratos. Elabora&ccedil;&atilde;o de regras de campanhas 
                        de marketing e obten&ccedil;&atilde;o de  aprova&ccedil;&atilde;o de sorteios perante a Caixa Econ&ocirc;mica Federal.</p>
                    </div>
                </div>
        </div>

        <div class="area">
            <a href="#modalTerceiroSetor"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/terceirosetor.png')}}"><h3>TERCEIRO SETOR</h3></a>
                <div id="modalTerceiroSetor" class="modalDialog">
                    <div class="areatxt">
                        <a href="#close" title="Close" class="close">X</a>
                        <div class="imagemarea"><img title="advogados" alt="advogados" src="{{url('assets/imagens/areas/terceirosetor.png')}}"></div>
                        <h2>TERCEIRO SETOR</h2>
                        <p>Negocia&ccedil;&atilde;o de  conv&ecirc;nios e parcerias com entidades p&uacute;blicas e n&atilde;o-governamentais; assessoria e  constitui&ccedil;&atilde;o 
                        de ONG, Associa&ccedil;&otilde;es, aconselhamento sobre a Lei Roaunet e Lei do  Esporte.</p>
                    </div>
                </div>
        </div>


    </div>
    <!-- Fim Areas -->
</div>

@endsection