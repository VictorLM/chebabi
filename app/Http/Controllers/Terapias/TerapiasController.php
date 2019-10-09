<?php

namespace Intranet\Http\Controllers\Terapias;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Intranet\Http\Controllers\Terapias\Terapia;
use Intranet\Terapias_QuickMassage;
use Intranet\Terapias_MassagemPes;
use Intranet\Terapias_Auriculoterapia;
use Intranet\Terapias_MassagemRelaxante;
use Intranet\Terapias_MatPilates;
use Validator;
use Exception;

/* 
Limite de uma terapia por dia, por usuário. Exceto Mat Pilates
Se houver sessão livre 30 min. antes do início da mesma, liberar também para os usuários com limites já atingidos
*/

class TerapiasController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //ADD MIDDLEWARE ADMIN_RECEPCAO PARA LISTAR TODOS AGENDAMENTO/ENVIAR E-MAIL E PARA INCLUIR DIAS SEM TERAPIAS//
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function index(){
        $title = 'Agendamento Terapias | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.terapias.index', compact('title'));
    }
    
    public function quick_massage_index(){
        /* De segunda a sexta
        Dias e horários definidos em $terapia['dias_horarios']
        Limite por usuário de 4 por mês
        15 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        ////////////////////////////PARÂMETROS/////////////////////////////
        $terapia = array();
        $terapia['tipo'] = "quick_massages";
        $terapia['limite_mensal'] = 4;
        $terapia['tempo_sessao'] = 15;
        $terapia['intervalo_agendamento'] = "semanal";
        $terapia['dias_horarios'] = array(
            '2' => array(
                'inicio' => '14:00',
                'fim' => '15:45',
                'intervalo' => false,
            ),
            '3' => array(
                'inicio' => '14:00',
                'fim' => '14:45',
                'intervalo' => false,
            ),
            '4' => array(
                'inicio' => '15:45',
                'fim' => '17:15',
                'intervalo' => false,
                //'intervalo' => array('inicio' => '16:00','fim' => '16:15',),
            ),
            '5' => array(
                'inicio' => '10:00',
                'fim' => '10:45',
                'intervalo' => false,
            ),
            '6' => array(
                'inicio' => '14:50',
                'fim' => '17:20',
                'intervalo' => false,
            ),
        );
        $quick_massage = new Terapia($terapia);
        $quick_massage_array = $quick_massage->buildArray();
        $title = 'Agendamento Quick Massage | Intranet Izique Chebabi Advogados Associados';
        $limite_livre_bonus = 30; // minutos
        return view('intranet.terapias.quick_massage_index', compact('title','quick_massage_array','terapia','limite_livre_bonus'));
    }

    public function agendar_quick_massage(Request $request){
        $validacao = Terapia::validator($request->all());
        if(!$validacao->fails()){
            ////
            $tipo = "quick_massages";
            $limite_mensal = 4; // limite mensal por usuário de sessões de terapia quick massage
            $intervalo_agendamento = "semanal";
            $tempo_sessao = 15;
            $limite_livre_bonus = 30; // minutos
            $lembrete_minutos_antes_inicio = 10; // Tempo em minutos para aparecer o pop lembrete no Outlook 
            ////
            $dia_sem_terapia = Terapia::dia_sem_terapia($request->data, $tipo); // retorna int/boolean
            if(!$dia_sem_terapia){
                $sessao_livre = Terapia::sessao_livre($tipo, $request->data, $request->hora); // retorna boolean
                if($sessao_livre){
                    $terapias_usuario_dia = Terapia::terapias_usuario_dia($request->data); // retorna string/boolean
                    $sessoes_usuario_mes = Terapia::terapias_usuario_mes($tipo, $request->data); // retorna int/boolean
                    $limite_intervalo_agendamento = Terapia::limite_intervalo_agendamento($tipo,$intervalo_agendamento,$request->data); // retorna int/boolean
                        if($terapias_usuario_dia){
                            return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Limite diário de agendamentos atingido.'));
                        }elseif($sessoes_usuario_mes < $limite_mensal && !$limite_intervalo_agendamento){
                            $resultado = Terapia::agendar_sessao($tipo, $request->data, $request->hora, $tempo_sessao, false, $lembrete_minutos_antes_inicio); /* retorna boolean/error */
                            if(empty($resultado["error"]) && $resultado = "agendado"){
                                $request->session()->flash('alert-success', 'Sessão de Quick Massage agendada com sucesso!');
                                return redirect('/intranet/terapias');
                            }else{
                                return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                            }
                        }else{
                            $livre_bonus = Terapia::livre_bonus($request->data, $request->hora, $limite_livre_bonus); // retorna boolean
                            if($livre_bonus){
                                $resultado = Terapia::agendar_sessao($tipo, $request->data, $request->hora, $tempo_sessao, true, $lembrete_minutos_antes_inicio); /* retorna string/array */
                                if(empty($resultado["error"]) && $resultado = "agendado"){
                                    $request->session()->flash('alert-success', 'Sessão bônus de Quick Massage agendada com sucesso!');
                                    return redirect('/intranet/terapias');
                                }else{
                                    return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                                }
                            }else{
                                return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Limite semanal ou mensal de agendamentos atingido.'));
                            }
                        }
                }else{
                    return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Esta sessão já foi agendada por outro usuário.'));
                }
            }else{
                return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Não haverá sessões no dia selecionado.'));
            }
        }else{
            return redirect()->back()->withErrors($validacao);
        }
    }

    public function cancelar_quick_massage(Request $request){
        $validacao = Terapia::validator($request->all());
        if(!$validacao->fails()){
            if(Carbon::parse($request->data." ".$request->hora)->isFuture()){ // checa se sessão não é passado
                $tipo = "quick_massages"; 
                $resultado = Terapia::cancelar_sessao($tipo, $request->data, $request->hora); /* retorna string/array */
                if(empty($resultado["error"]) && $resultado = "cancelado"){
                    $request->session()->flash('alert-success', 'Sessão de Quick Massage cancelada com sucesso!');
                    return redirect('/intranet/terapias');
                }else{
                    return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                }
            }else{
                return redirect()->back()->withErrors(array('message' => 'Erro ao cancelar! Esta sessão já passou.'));
            }
        }else{
            return redirect()->back()->withErrors($validacao);
        }
    }

    public function auriculoterapia_index(){
        /* De segunda, terça, quinta e sexta
        Dias e horários definidos em $terapia['dias_horarios']
        Limite por usuário de 5 por mês
        20 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        ////////////////////////////PARÂMETROS/////////////////////////////
        $terapia = array();
        $terapia['tipo'] = "auriculoterapias";
        $terapia['limite_mensal'] = 5; //uma sessão a cada 7 dias, tem mês com 5 semanas
        $terapia['tempo_sessao'] = 20;
        $terapia['intervalo_agendamento'] = 7; //uma sessão a cada 7 dias
        $terapia['dias_horarios'] = array(
            '2' => array(
                'inicio' => '09:00',
                'fim' => '12:40',
                'intervalo' => false,
            ),
            '3' => array(
                'inicio' => '09:00',
                'fim' => '11:40',
                'intervalo' => false,
                //'intervalo' => array('inicio' => '16:00','fim' => '16:15',),
            ),
            '5' => array(
                'inicio' => '08:00',
                'fim' => '09:40',
                'intervalo' => false,
            ),
            '6' => array(
                'inicio' => '12:00',
                'fim' => '14:20',
                'intervalo' => false,
            ),
        );
        $auriculoterapia = new Terapia($terapia);
        $auriculoterapia_array = $auriculoterapia->buildArray();
        $limite_livre_bonus = 30; // minutos
        $title = 'Agendamento Auriculoterapia | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.terapias.auriculoterapia_index', compact('title','auriculoterapia_array','terapia','limite_livre_bonus'));
    }

    public function agendar_auriculoterapia(Request $request){
        $validacao = Terapia::validator($request->all());
        if(!$validacao->fails()){
            ////
            $tipo = "auriculoterapias";
            $limite_mensal = 5; // limite mensal por usuário de sessões de terapia quick massage
            $intervalo_agendamento = 7;
            $tempo_sessao = 20;
            $limite_livre_bonus = 30; // minutos
            $lembrete_minutos_antes_inicio = 10; // Tempo em minutos para aparecer o pop lembrete no Outlook 
            ////
            $dia_sem_terapia = Terapia::dia_sem_terapia($request->data, $tipo); // retorna int/boolean
            if(!$dia_sem_terapia){
                $sessao_livre = Terapia::sessao_livre($tipo, $request->data, $request->hora); // retorna boolean
                if($sessao_livre){
                    $terapias_usuario_dia = Terapia::terapias_usuario_dia($request->data); // retorna string/boolean
                    $sessoes_usuario_mes = Terapia::terapias_usuario_mes($tipo, $request->data); // retorna int/boolean
                    $limite_intervalo_agendamento = Terapia::limite_intervalo_agendamento($tipo,$intervalo_agendamento,$request->data); // retorna int/boolean
                        if($terapias_usuario_dia){
                            return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Limite diário de agendamentos atingido.'));
                        }elseif($sessoes_usuario_mes < $limite_mensal && !$limite_intervalo_agendamento){
                            $resultado = Terapia::agendar_sessao($tipo, $request->data, $request->hora, $tempo_sessao, false, $lembrete_minutos_antes_inicio); /* retorna boolean/error */
                            if(empty($resultado["error"]) && $resultado = "agendado"){
                                $request->session()->flash('alert-success', 'Sessão de Auriculoterapia agendada com sucesso!');
                                return redirect('/intranet/terapias');
                            }else{
                                return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                            }
                        }else{
                            $livre_bonus = Terapia::livre_bonus($request->data, $request->hora, $limite_livre_bonus); // retorna boolean
                            if($livre_bonus && !$limite_intervalo_agendamento){ // aqui muda por conta do intervalo obrigatório de 7 dias
                                $resultado = Terapia::agendar_sessao($tipo, $request->data, $request->hora, $tempo_sessao, true, $lembrete_minutos_antes_inicio); /* retorna string/array */
                                if(empty($resultado["error"]) && $resultado = "agendado"){
                                    $request->session()->flash('alert-success', 'Sessão bônus de Auriculoterapia agendada com sucesso!');
                                    return redirect('/intranet/terapias');
                                }else{
                                    return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                                }
                            }else{
                                return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Limite semanal ou mensal de agendamentos atingido.'));
                            }
                        }
                }else{
                    return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Esta sessão já foi agendada por outro usuário.'));
                }
            }else{
                return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Não haverá sessões no dia selecionado.'));
            }
        }else{
            return redirect()->back()->withErrors($validacao);
        }
    }

    public function cancelar_auriculoterapia(Request $request){
        $validacao = Terapia::validator($request->all());
        if(!$validacao->fails()){
            if(Carbon::parse($request->data." ".$request->hora)->isFuture()){ // checa se sessão não é passado
                $tipo = "auriculoterapias"; 
                $resultado = Terapia::cancelar_sessao($tipo, $request->data, $request->hora); /* retorna string/array */
                if(empty($resultado["error"]) && $resultado = "cancelado"){
                    $request->session()->flash('alert-success', 'Sessão de Auriculoterapia cancelada com sucesso!');
                    return redirect('/intranet/terapias');
                }else{
                    return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                }
            }else{
                return redirect()->back()->withErrors(array('message' => 'Erro ao cancelar! Esta sessão já passou.'));
            }
        }else{
            return redirect()->back()->withErrors($validacao);
        }
    }

    public function massagem_pes_index(){
        /* De segunda, terça, quinta e sexta
        Dias e horários definidos em $terapia['dias_horarios']
        Limite por usuário de 5 por mês
        20 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        ////////////////////////////PARÂMETROS/////////////////////////////
        $terapia = array();
        $terapia['tipo'] = "massagens_pes";
        $terapia['limite_mensal'] = 1;
        $terapia['tempo_sessao'] = 15;
        $terapia['intervalo_agendamento'] = false; //uma sessão por mês apenas
        $terapia['dias_horarios'] = array(
            '4' => array(
                'inicio' => '13:00',
                'fim' => '15:15',
                'intervalo' => false,
                //'intervalo' => array('inicio' => '16:00','fim' => '16:15',),
            ),
        );
        $massagem_pes = new Terapia($terapia);
        $massagem_pes_array = $massagem_pes->buildArray();
        $limite_livre_bonus = 30; // minutos
        $title = 'Agendamento Massagem nos Pés | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.terapias.massagem_pes_index', compact('title','massagem_pes_array','terapia','limite_livre_bonus'));
    }

    public function agendar_massagem_pes(Request $request){
        $validacao = Terapia::validator($request->all());
        if(!$validacao->fails()){
            ////
            $tipo = "massagens_pes";
            $limite_mensal = 1;
            $tempo_sessao = 15;
            $limite_livre_bonus = 30; // minutos
            $lembrete_minutos_antes_inicio = 10; // Tempo em minutos para aparecer o pop lembrete no Outlook 
            ////
            $dia_sem_terapia = Terapia::dia_sem_terapia($request->data, $tipo); // retorna int/boolean
            if(!$dia_sem_terapia){
                $sessao_livre = Terapia::sessao_livre($tipo, $request->data, $request->hora); // retorna boolean
                if($sessao_livre){
                    $terapias_usuario_dia = Terapia::terapias_usuario_dia($request->data); // retorna string/boolean
                    $sessoes_usuario_mes = Terapia::terapias_usuario_mes($tipo, $request->data); // retorna int/boolean
                        if($terapias_usuario_dia){
                            return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Limite diário de agendamentos atingido.'));
                        }elseif($sessoes_usuario_mes < $limite_mensal){
                            $resultado = Terapia::agendar_sessao($tipo, $request->data, $request->hora, $tempo_sessao, false, $lembrete_minutos_antes_inicio); /* retorna boolean/error */
                            if(empty($resultado["error"]) && $resultado = "agendado"){
                                $request->session()->flash('alert-success', 'Sessão de Massagem nos Pés agendada com sucesso!');
                                return redirect('/intranet/terapias');
                            }else{
                                return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                            }
                        }else{
                            $livre_bonus = Terapia::livre_bonus($request->data, $request->hora, $limite_livre_bonus); // retorna boolean
                            if($livre_bonus){
                                $resultado = Terapia::agendar_sessao($tipo, $request->data, $request->hora, $tempo_sessao, true, $lembrete_minutos_antes_inicio); /* retorna string/array */
                                if(empty($resultado["error"]) && $resultado = "agendado"){
                                    $request->session()->flash('alert-success', 'Sessão bônus de Massagem nos Pés agendada com sucesso!');
                                    return redirect('/intranet/terapias');
                                }else{
                                    return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                                }
                            }else{
                                return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Limite semanal ou mensal de agendamentos atingido.'));
                            }
                        }
                }else{
                    return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Esta sessão já foi agendada por outro usuário.'));
                }
            }else{
                return redirect()->back()->withErrors(array('message' => 'Erro ao agendar! Não haverá sessões no dia selecionado.'));
            }
        }else{
            return redirect()->back()->withErrors($validacao);
        }
    }

    public function cancelar_massagem_pes(Request $request){
        $validacao = Terapia::validator($request->all());
        if(!$validacao->fails()){
            if(Carbon::parse($request->data." ".$request->hora)->isFuture()){ // checa se sessão não é passado
                $tipo = "massagens_pes"; 
                $resultado = Terapia::cancelar_sessao($tipo, $request->data, $request->hora); /* retorna string/array */
                if(empty($resultado["error"]) && $resultado = "cancelado"){
                    $request->session()->flash('alert-success', 'Sessão de Massagem nos Pés cancelada com sucesso!');
                    return redirect('/intranet/terapias');
                }else{
                    return redirect()->back()->withErrors(array('message' => $resultado["error"]));
                }
            }else{
                return redirect()->back()->withErrors(array('message' => 'Erro ao cancelar! Esta sessão já passou.'));
            }
        }else{
            return redirect()->back()->withErrors($validacao);
        }
    }

    public function massagem_relaxante_index(){
        /* Quartas e sextas
        Dias e horários definidos em $terapia['dias_horarios']
        Uma sessão por equipe, até todos de todas as equipes terem feito (???)
        30 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        
        dd("TESTE");
    }


    ////////////////////////////////////////////////
    public function enviar_lista_agendamentos(){
        //TODO
    }
    ////////////////////////////////////////////////

    /*
    public function todos_agendamentos_massagem(){
        //CHECA PERFIL PARA EXIBIR LINK DA VIEW C/ TODOS OS AGENDAMENTOS
        if(Auth::user()->tipo == 'admin' || Auth::user()->email == 'recepcao@chebabi.com'){

            $dias_sem_massagem = DB::table('dias_sem_massagens')
                ->whereMonth('data', '>=', Carbon::now()->month)
                ->whereYear('data', '>=', Carbon::now()->year)
                ->orderBy('data', 'asc')
                ->get();

            $massagens_agendadas = Massagem::with('user:id,name')
                ->where('cancelado', false)
                ->whereDate('inicio_data', '>=', Carbon::today())
                ->orderBy('inicio_data', 'asc')
                ->orderBy('inicio_hora', 'asc')
                ->get();

            $title = 'Todos Agendamentos Massagem | Intranet Izique Chebabi Advogados Associados';
            return view('intranet.todos_agendamentos_massagem', compact('title','dias_sem_massagem','massagens_agendadas'));

        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function form_dia_sem_massagem(Request $request){
        //CHECA PERFIL PARA EXIBIR LINK DA VIEW C/ TODOS OS AGENDAMENTOS
        if(Auth::user()->tipo == 'admin' || Auth::user()->email == 'recepcao@chebabi.com'){

            $dias_sem_massagem = DB::table('dias_sem_massagens')
                ->whereMonth('data', '>=', Carbon::now()->month)
                ->whereYear('data', '>=', Carbon::now()->year)
                ->orderBy('data', 'asc')
                ->get();
            
            $title = 'Incluir dia sem Massagem | Intranet Izique Chebabi Advogados Associados';
            return view('intranet.form_dia_sem_massagem', compact('title','dias_sem_massagem'));

        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function incluir_dia_sem_massagem(Request $request){

        if(Auth::user()->tipo == 'admin' || Auth::user()->email == 'recepcao@chebabi.com'){

            $validatedData = Validator::make($request->all(), [
                'data' => 'required|date_format:Y-m-d|after_or_equal:today|unique:dias_sem_massagens',
            ]);
            if (!$validatedData->fails()){
    
                DB::table('dias_sem_massagens')->insert([
                    'data' => $request->data,
                    'usuario' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
    
                $request->session()->flash('alert-success', 'Data inclusa com sucesso!');
                return redirect()->action('Intranet\IntranetController@form_dia_sem_massagem');
            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }
        }else{
            return abort(403, 'Não autorizado.');
        }
    }
    */



}
