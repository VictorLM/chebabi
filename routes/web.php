<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('intranet/inserir-custas', 'APIs\LegalOneController@teste');


//ROTAS DO SITE
Route::get('/', 'Site\SiteController@index');
Route::get('/advogados', 'Site\SiteController@equipe');
Route::get('/areas', 'Site\SiteController@areas');
Route::get('/contato', 'Site\SiteController@contato');
Route::get('/trabalhe-conosco', 'Site\SiteController@trabalheconosco');
Route::get('/escritorios', 'Site\SiteController@escritorios');
Route::get('/noticias', 'Site\SiteController@noticias');
Route::post('/enviar_contato', 'Site\SiteController@enviar_contato');
Route::post('/enviar_curriculo', 'Site\SiteController@enviar_curriculo');
//ROTAS DA INTRANET
Route::group(['prefix'=>'intranet'],function(){
    Route::get('/', 'Intranet\IntranetController@index');
    Route::get('/sugestao', 'Intranet\IntranetController@sugestao');
    Route::post('/enviar-sugestao', 'Intranet\IntranetController@enviar_sugestao');
    //ROTAS AGENDA
    Route::get('/agenda', 'Intranet\IntranetController@agenda');
    Route::post('/eventos', 'Intranet\IntranetController@eventos');
    Route::get('/agenda/evento/{id}', 'Intranet\IntranetController@showEvento');
    Route::get('/agenda/evento/{id}/editar', 'Intranet\IntranetController@editEvento');
    Route::get('/agenda/evento/{id}/cancelar', 'APIs\MicrosoftController@cancela_evento');
    Route::get('/agenda/novo-evento', 'Intranet\IntranetController@novo_evento');
    Route::post('/agenda/novo-evento', 'APIs\MicrosoftController@criar_evento');
    Route::post('/agenda/editar-evento', 'APIs\MicrosoftController@update_evento');
    //ROTAS LEGAL ONE
    Route::get('/andamentos-datacloud', 'APIs\LegalOneController@andamentos_datacloud');
    //ESSA ROTA ABAIXO GET É PARA O PAGINATION FUNCIONAR
    Route::get('/andamentos-datacloud/filtrar', 'APIs\LegalOneController@andamentos_datacloud_filtrados');
    Route::post('/andamentos-datacloud/filtrar', 'APIs\LegalOneController@andamentos_datacloud_filtrados');
    Route::get('/andamentos-datacloud/andamento/{id}', 'APIs\LegalOneController@showAndamento');
    Route::get('/inserir-andamentos', 'APIs\LegalOneController@andamentos');
    Route::post('/get-pasta-id', 'APIs\LegalOneController@id_pasta');//AJAX
    Route::post('/andamento', 'APIs\LegalOneController@inserir_andamentos');
    Route::get('/inserir-custas', 'APIs\LegalOneController@custas');
    Route::post('/inserir-custas', 'APIs\LegalOneController@inserir_custas');
    //DEMAIS ROTAS
    Route::get('/aniversariantes', 'Intranet\IntranetController@aniversariantes');
    //ESSA ROTA ABAIXO GET É PARA O PAGINATION FUNCIONAR
    Route::get('/aniversariantes/filtrar', 'Intranet\IntranetController@aniversariantes_filtrados');    
    Route::post('/aniversariantes/filtrar', 'Intranet\IntranetController@aniversariantes_filtrados');  
    Route::get('/contatos', 'Intranet\IntranetController@contatos');
    Route::get('/procedimentos', 'Intranet\IntranetController@procedimentos');
    Route::get('/tarifadores', 'Intranet\IntranetController@tarifadores');
    Route::get('/tutoriais', 'Intranet\IntranetController@tutoriais');
    Route::get('/relatorio', 'Intranet\IntranetController@relatorio');
    Route::get('/uau', 'Intranet\IntranetController@uau');
    Route::get('/meus-uaus', 'Intranet\IntranetController@meus_uaus');
    Route::get('/novo-uau', 'Intranet\IntranetController@novo_uau');
    Route::post('/enviar_uau', 'Intranet\IntranetController@enviar_uau');
    //ROTAS AJAX
    Route::post('/call_legalone_api', 'APIs\LegalOneController@legalone_api');
    Route::post('/call_legalone_api_contacts', 'APIs\LegalOneController@legalone_api_contacts');
    Route::post('/call_legalone_api_contacts_individuals', 'APIs\LegalOneController@legalone_api_contacts_individuals');
    Route::post('/get-credores', 'APIs\LegalOneController@get_credores');
    Route::post('/get-tipo-gasto', 'APIs\LegalOneController@get_tipos_gastos');
    Route::post('/uau_lido', 'Intranet\IntranetController@uau_lido');
    //ROTAS DE RETORNO DOS PDFS (TUTORIAIS E PROCEDIMENTOS)
    Route::get('/pdf/{nomepasta}/{nomepdf}', 'Intranet\IntranetController@tutoriais_pdf');
    Route::get('/pdf/{nomepasta}/{nomesubpasta}/{nomepdf}', 'Intranet\IntranetController@pdf');
    //ROTAS RELATÓRIO CONTROLLER
    Route::post('/relatorio/enviar', 'Relatorio\RelatorioController@create');
});
//ROTAS DO PAINEL DO ADMINISTRADOR
Route::group(['prefix'=>'intranet/admin'],function(){
    //INDEX PAINEL ADM
    Route::get('/', 'Admin\AdminController@index');
    //ROTAS CRUD USERS
    Route::resource('/users', 'User\UserController');
    Route::put('/users/active/{id}', 'User\UserController@active');
    //ROTAS CRUD ADVOGADOS
    Route::resource('/advs', 'Advogados\AdvogadosController');
    //ROTAS CADASTRO E DELEÇÃO PROCEDIMENTOS
    Route::get('/novo_procedimento', 'Admin\AdminController@form_procedimento');
    Route::post('/novo_procedimento', 'Admin\AdminController@novo_procedimento');
    Route::get('/del_procedimento', 'Admin\AdminController@show_procedimento');
    Route::post('/del_procedimento', 'Admin\AdminController@del_procedimento');
    //ROTAS CADASTRO E DELEÇÃO TARIFADORES
    Route::get('/novo_tarifador', 'Admin\AdminController@form_tarifador');
    Route::post('/novo_tarifador', 'Admin\AdminController@novo_tarifador');
    Route::get('/del_tarifador', 'Admin\AdminController@show_tarifador');
    Route::post('/del_tarifador', 'Admin\AdminController@del_tarifador');
    //ROTAS CADASTRO E DELEÇÃO TUTORIAIS
    Route::get('/novo_tutorial', 'Admin\AdminController@form_tutorial');
    Route::post('/novo_tutorial', 'Admin\AdminController@novo_tutorial');
    Route::get('/del_tutorial', 'Admin\AdminController@show_tutorial');
    Route::post('/del_tutorial', 'Admin\AdminController@del_tutorial');
    //ROTAS RELATÓRIO CONTROLLER
    Route::get('/relatorios', 'Relatorio\RelatorioController@relatorios');
    //ESSA ROTA ABAIXO GET É PARA O PAGINATION FUNCIONAR
    Route::get('/relatorios/filtrar', 'Relatorio\RelatorioController@relatorios_user');    
    Route::post('/relatorios/filtrar', 'Relatorio\RelatorioController@relatorios_user');    
    //ROTA PRA SEEDAR O NOVO BD - APAGAR
    //Route::get('/seed', 'Admin\AdminController@seed');
});
//OVERWRITE DAS ROTAS PADRÃO DO AUTH
// Authentication Routes...
$this->get('intranet/login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('intranet/login', 'Auth\LoginController@login');
$this->post('intranet/logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
$this->get('intranet/admin/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('intranet/admin/register', 'Auth\RegisterController@register');