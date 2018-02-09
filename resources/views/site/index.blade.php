@extends ('site.templates.template-home')

@section('conteudo')

<div id="conteudo">
    <!-- Escritório -->
    <h1>O ESCRITÓRIO</h1>
    <div id="sobre">

        <div id="texto">
            <p>Fundado em 1998, o escritório <strong>IZIQUE CHEBABI ADVOGADOS ASSOCIADOS</strong> re&uacute;ne a  
            experi&ecirc;ncia de mais de 30 anos de sua s&oacute;cia <strong>MARILDA IZIQUE CHEBABI</strong>,  
            Desembargadora do Tribunal Regional do Trabalho da 15a Regi&atilde;o,  aposentada, ex-professora da 
            Escola Superior da Magistratura, de cursos de  p&oacute;s-gradua&ccedil;&atilde;o e autora de in&uacute;meros 
            trabalhos publicados na &aacute;rea  trabalhista.</p><br/>

            <p>Ao lado da reconhecida trajet&oacute;ria da s&oacute;cia fundadora, o  escrit&oacute;rio conta com advogados e 
            consultores com s&oacute;lida e cont&iacute;nua forma&ccedil;&atilde;o  acad&ecirc;mica, especialistas na &aacute;rea 
            trabalhista, sindical, empresarial, c&iacute;vel,  banc&aacute;ria, comercial, contratual, tribut&aacute;ria, administrativa, 
            ambiental,  previdenci&aacute;ria e consumidor, com atua&ccedil;&atilde;o consultiva (pareceres e orienta&ccedil;&otilde;es) e  
            contenciosa (judicial e administrativa).</p><br/>

            <p>A busca por solu&ccedil;&otilde;es r&aacute;pidas e a constante preocupa&ccedil;&atilde;o com  resultados, 
            como a redu&ccedil;&atilde;o de passivos trabalhistas, c&iacute;veis e fiscais e a  elimina&ccedil;&atilde;o 
            de riscos jur&iacute;dicos e, ainda, os investimentos em ferramentas de  atualiza&ccedil;&atilde;o de processos pela internet, 
            com emiss&atilde;o peri&oacute;dica de relat&oacute;rios por  e-mail, resumem os tr&ecirc;s principais pontos de destaque do 
            escrit&oacute;rio: efici&ecirc;ncia,  compet&ecirc;ncia e transpar&ecirc;ncia.</p><br/>

            <p>Essa postura, aliada &agrave;s constantes orienta&ccedil;&otilde;es voltadas &agrave; tomada  de decis&otilde;es estrat&eacute;gicas, 
            com suporte jur&iacute;dico a novos neg&oacute;cios, t&ecirc;m rendido ao  escrit&oacute;rio clientes destacados no cen&aacute;rio 
            nacional e internacional.</p><br/>

            <p>Pela privilegiada localiza&ccedil;&atilde;o de sua sede, na cidade de Campinas  e de suas filiais em S&atilde;o Paulo, 
            Florian&oacute;polis e Bebedouro, a menos de 100 km de S&atilde;o Jos&eacute; do Rio Preto, com  correspondentes em  
            Santos, Bauru, Ribeir&atilde;o Preto e Taubat&eacute;, o  escrit&oacute;rio atende a seus clientes nas principais cidades 
            e regi&otilde;es do Estado de  S&atilde;o Paulo.</p>
        </div>

        <div id="bodyimagens">
            <img alt="advogados" title="advogados" src="{{url('assets/imagens/body/b1.jpg')}}">
            <img alt="advogados" title="advogados" src="{{url('assets/imagens/body/b2.jpg')}}">
        </div>

    </div>

</div>

<div id="sobre2">
    <h2>MISSÃO</h2>
    <p><i> - Encantar o cliente com atendimento personalizado, oferecendo soluções eficientes para a defesa dos seus interesses.</i></p>
    <br/>
    <h2>VISÃO</h2>
    <p><i> - Ser a principal referência na prestação de serviços jurídicos na área de direito empresarial.</i></p>
    <br/>
    <h2>VALORES</h2>
    <ul>
        <li><i>	Admirar a profissão, o cliente e o colega de trabalho.</i></li>
        <li><i>	Agradar ao cliente com pro atividade.</i></li>
        <li><i>	Ouvir a opinião do cliente e a do colega de trabalho.</i></li>
        <li><i>	Servir os colegas de trabalho.</i></li>
    </ul>
</div>
<!-- Fim Escritório -->
@if(!empty($mensagem))
    @push ('scripts')
        <script type="text/javascript">
            alert('{{$mensagem}}');
        </script>
    @endpush
@endif

@endsection
