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
use Intranet\User;
use Validator;
use Exception;

/* 
Limite de uma terapia por dia, por usuário. Exceto Mat Pilates
Se houver sessão livre 30 min. antes do início da mesma, liberar também para os usuários com limites já atingidos
*/

class TerapiasController extends Controller{

    private $terapia_quick_massage = array();
    private $terapia_auriculoterapia = array();
    private $terapia_massagem_pes = array();

    public function __construct()
    {
        $this->middleware('auth');

        //////////// QUICK MASSAGE - PARÂMETROS ////////////
        /* De segunda, terça, quinta e sexta-feira
        Dias e horários definidos em $terapia['dias_horarios']
        Limite por usuário de 4 por mês
        15 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        $this->terapia_quick_massage['tipo'] = "quick_massages";
        $this->terapia_quick_massage['limite_mensal'] = 4;
        $this->terapia_quick_massage['tempo_sessao'] = 15;
        $this->terapia_quick_massage['intervalo_agendamento'] = "semanal";
        $this->terapia_quick_massage['dias_horarios'] = array(
            '2' => array(
                'inicio' => '14:00',
                'fim' => '16:00',
                'intervalo' => false,
            ),
            '3' => array(
                'inicio' => '16:30',
                'fim' => '18:00',
                'intervalo' => false,
            ),
            '4' => array(
                'inicio' => '14:00',
                'fim' => '15:45',
                'intervalo' => false,
            ),
            '5' => array(
                'inicio' => '15:45',
                'fim' => '18:00',
                'intervalo' => array('inicio' => '16:15','fim' => '16:30',),
            ),
            '6' => array(
                'inicio' => '14:00',
                'fim' => '16:00',
                'intervalo' => false,
            ),
        );
        ///////////////////////////////////////////////////////////////////

        //////////// AURICULOTERAPIA - PARÂMETROS ////////////
        /* De segunda, terça, quinta e sexta
        Dias e horários definidos em $terapia['dias_horarios']
        Limite por usuário de 5 por mês
        20 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        
        $this->terapia_auriculoterapia['tipo'] = "auriculoterapias";
        $this->terapia_auriculoterapia['limite_mensal'] = 5; //uma sessão a cada 7 dias, tem mês com 5 semanas
        $this->terapia_auriculoterapia['tempo_sessao'] = 15;
        $this->terapia_auriculoterapia['intervalo_agendamento'] = 7; //uma sessão a cada 7 dias
        $this->terapia_auriculoterapia['dias_horarios'] = array(
            '2' => array(
                'inicio' => '16:30',
                'fim' => '18:00',
                'intervalo' => false,
            ),
            '3' => array(
                'inicio' => '14:00',
                'fim' => '16:00',
                'intervalo' => array('inicio' => '15:00','fim' => '15:30',),
            ),
            '5' => array(
                'inicio' => '14:30',
                'fim' => '15:30',
                'intervalo' => false,
            ),
            '6' => array(
                'inicio' => '16:30',
                'fim' => '17:30',
                'intervalo' => false,
            ),
        );
        ///////////////////////////////////////////////////////////////////
        
        //////////// MASSAGEM NOS PÉS - PARÂMETROS ////////////
        /* De segunda, terça, quinta e sexta
        Dias e horários definidos em $terapia['dias_horarios']
        Limite por usuário de 5 por mês
        20 minutos por sessão */
        // puxas parâmetros do BD para modularizar ainda mais // TODO
        ////////////////////////////PARÂMETROS/////////////////////////////
        
        $this->terapia_massagem_pes['tipo'] = "massagens_pes";
        $this->terapia_massagem_pes['limite_mensal'] = 1;
        $this->terapia_massagem_pes['tempo_sessao'] = 15;
        $this->terapia_massagem_pes['intervalo_agendamento'] = false; //uma sessão por mês apenas
        $this->terapia_massagem_pes['dias_horarios'] = array(
            '4' => array(
                'inicio' => '16:15',
                'fim' => '17:30',
                'intervalo' => false,
                //'intervalo' => array('inicio' => '16:00','fim' => '16:15',),
            ),
        );

    }

    public function index(){
        $title = 'Agendamento Terapias | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.terapias.index', compact('title'));
    }
    
    public function quick_massage_index(){
        $terapia = $this->terapia_quick_massage;
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
        $terapia = $this->terapia_auriculoterapia;
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
        $terapia = $this->terapia_massagem_pes;
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
        //TODO
        dd("TESTE");
    }


    public function painel_admin(){
        /*
        TODOS OS AGENDAMENTOS DO DIA POR TIPO + FORM ENVIAR LISTA POR E-MAIL
        DIAS SEM TERAPIA POR TIPO + FORM INCLUIR DIA SEM TERAPIA (TIPO)
        GRÁFICOS MÊS: 
            AGENDAMENTOS POR TERAPIA
            AGENDAMENTOS POR USUÁRIO
            AGENDADAS, CANCELADAS, BONUS
        */

        if(Auth::user()->is_admin_recepcao()){

            //QUICK MASSAGE
            $terapia_qm = $this->terapia_quick_massage;
            $quick_massage = new Terapia($terapia_qm);
            $quick_massage_array = $quick_massage->buildArray();
            foreach($quick_massage_array['dias'] as $key => $dia){
                if(!Carbon::parse($dia['dia'])->isToday()){
                    unset($quick_massage_array['dias'][$key]);
                }
            }
            $quick_massage_array['tempo_sessao'] = $terapia_qm['tempo_sessao'];

            //AURICULOTERAPIA
            $terapia_at = $this->terapia_auriculoterapia;
            $auriculoterapia = new Terapia($terapia_at);
            $auriculoterapia_array = $auriculoterapia->buildArray();
            foreach($auriculoterapia_array['dias'] as $key => $dia){
                if(!Carbon::parse($dia['dia'])->isToday()){
                    unset($auriculoterapia_array['dias'][$key]);
                }
            }
            $auriculoterapia_array['tempo_sessao'] = $terapia_at['tempo_sessao'];

            //MASSAGEM NOS PÉS
            $terapia_mp = $this->terapia_massagem_pes;
            $massagem_pes = new Terapia($terapia_mp);
            $massagem_pes_array = $massagem_pes->buildArray();
            foreach($massagem_pes_array['dias'] as $key => $dia){
                if(!Carbon::parse($dia['dia'])->isToday()){
                    unset($massagem_pes_array['dias'][$key]);
                }
            }
            $massagem_pes_array['tempo_sessao'] = $terapia_mp['tempo_sessao'];

            $dias_sem_terapias = DB::table('terapias_dias_sem_terapias')
                ->whereMonth('data', '=', Carbon::now()->month)
                ->whereYear('data', '=', Carbon::now()->year)
                ->join('users', 'terapias_dias_sem_terapias.usuario', '=', 'users.id')
                ->select('terapias_dias_sem_terapias.*', 'users.name')
                ->orderBy('data', 'asc')
                ->get();

            $title = 'Painel do Administrador | Intranet Izique Chebabi Advogados Associados';
            return view('intranet.terapias.painel_admin', compact('title', 'quick_massage_array', 'auriculoterapia_array', 'massagem_pes_array', 'dias_sem_terapias'));

        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function painel_admin_terapias_charts(Request $request){

        if($request->has('mes') && $request->has('ano')){

            $validatedData = Validator::make($request->all(), [
                'mes' => 'in:1,2,3,4,5,6,7,8,9,10,11,12',
                'ano' => 'digits:4|integer|min:2019',
            ]);
            
            if(!$validatedData->fails()){
                $mes = $request->mes;
                $ano = $request->ano;
            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }
        }else{
            $mes = Carbon::now()->month;
            $ano = Carbon::now()->year;
        }

        if(Auth::user()->is_admin_recepcao()){

            /* TODO
            Terapias_MassagemRelaxante;
            Terapias_MatPilates;
            */

            $array = [];
            //resumo_terapias
            $array['resumo_terapias']['terapias'][] = 'Quick Massage';
            $array['resumo_terapias']['terapias'][] = 'Auriculoterapia';
            $array['resumo_terapias']['terapias'][] = 'Massagem nos Pés';
            
            $array['resumo_terapias']['sessoes']['Agendadas'][] = Terapias_QuickMassage::where('cancelado', false)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            $array['resumo_terapias']['sessoes']['Canceladas'][] = Terapias_QuickMassage::where('cancelado', true)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            $array['resumo_terapias']['sessoes']['Bônus'][] = Terapias_QuickMassage::where('cancelado', false)
                ->where('livre_bonus', true)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            
            $array['resumo_terapias']['sessoes']['Agendadas'][] = Terapias_Auriculoterapia::where('cancelado', false)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            $array['resumo_terapias']['sessoes']['Canceladas'][] = Terapias_Auriculoterapia::where('cancelado', true)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            $array['resumo_terapias']['sessoes']['Bônus'][] = Terapias_Auriculoterapia::where('cancelado', false)
                ->where('livre_bonus', true)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            
            $array['resumo_terapias']['sessoes']['Agendadas'][]  = Terapias_MassagemPes::where('cancelado', false)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            $array['resumo_terapias']['sessoes']['Canceladas'][] = Terapias_MassagemPes::where('cancelado', true)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            $array['resumo_terapias']['sessoes']['Bônus'][] = Terapias_MassagemPes::where('cancelado', false)
                ->where('livre_bonus', true)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->count();
            
            //agendamentos_por_usuario
            
            $users = User::select('id', 'name')->where('ativo', TRUE)->orderBy('name')->get();

            foreach($users as $user){
                $array['agendamentos_por_usuario'][$user->name] = 0;
            }

            $qm_users = Terapias_QuickMassage::with('user:id,name')
                ->where('cancelado', false)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->select('usuario', DB::raw('count(*) as sessoes'))
                ->groupBy('usuario')
                //->orderBy('sessoes', 'DESC')
                ->get();

            foreach($qm_users as $user){
                if(array_key_exists($user->user->name, $array['agendamentos_por_usuario'])){
                    $array['agendamentos_por_usuario'][$user->user->name] += $user->sessoes;
                }
            }

            $at_users = Terapias_Auriculoterapia::with('user:id,name')
                ->where('cancelado', false)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->select('usuario', DB::raw('count(*) as sessoes'))
                ->groupBy('usuario')
                ->get();
            
            foreach($at_users as $user){
                if(array_key_exists($user->user->name, $array['agendamentos_por_usuario'])){
                    $array['agendamentos_por_usuario'][$user->user->name] += $user->sessoes;
                }
            }

            $mp_users = Terapias_MassagemPes::with('user:id,name')
                ->where('cancelado', false)
                ->whereMonth('inicio_data', '=', $mes)
                ->whereYear('inicio_data', '=', $ano)
                ->select('usuario', DB::raw('count(*) as sessoes'))
                ->groupBy('usuario')
                ->get();

            foreach($mp_users as $user){
                if(array_key_exists($user->user->name, $array['agendamentos_por_usuario'])){
                    $array['agendamentos_por_usuario'][$user->user->name] += $user->sessoes;
                }
            }

            //ORDER BY NAME E SESSOES DESC
            array_multisort(array_values($array['agendamentos_por_usuario']), SORT_DESC, array_keys($array['agendamentos_por_usuario']), SORT_ASC, $array['agendamentos_por_usuario']);
            //sessoes_por_usuario_e_por_tipo_terapia
            $array['sessoes_por_usuario_e_por_tipo_terapia']['temp'] = array_slice($array['agendamentos_por_usuario'], 0, 15);
            //LIMITE TOP 10
            $array['agendamentos_por_usuario'] = array_slice($array['agendamentos_por_usuario'], 0, 10);
            //ABREVIANDO NOMES
            foreach($array['agendamentos_por_usuario'] as $user => $sessoes){
                $array['agendamentos_por_usuario'][substr($user, 0, strpos($user, " ") + 2)."."] = $sessoes;
                unset($array['agendamentos_por_usuario'][$user]);
            }
            
            //sessoes_por_usuario_e_por_tipo_terapia

            $users = User::select('id', 'name')->where('ativo', TRUE)->orderBy('name')->get();

            foreach($array['sessoes_por_usuario_e_por_tipo_terapia']['temp'] as $nome => $sessoes){
                foreach($users as $user){
                    if($user->name == $nome){
                        $array['sessoes_por_usuario_e_por_tipo_terapia']['ids'][$nome] = $user->id;
                    }
                }
                $array['sessoes_por_usuario_e_por_tipo_terapia']['users'][] = $nome;
            }

            unset($array['sessoes_por_usuario_e_por_tipo_terapia']['temp']);

            foreach($array['sessoes_por_usuario_e_por_tipo_terapia']['ids'] as $id){

                $array['sessoes_por_usuario_e_por_tipo_terapia']['terapias']['Quick Massage'][] = Terapias_QuickMassage::where('cancelado', false)
                    ->where('usuario', $id)
                    ->whereMonth('inicio_data', '=', $mes)
                    ->whereYear('inicio_data', '=', $ano)
                    ->count();

                $array['sessoes_por_usuario_e_por_tipo_terapia']['terapias']['Auriculoterapia'][] = Terapias_Auriculoterapia::where('cancelado', false)
                    ->where('usuario', $id)
                    ->whereMonth('inicio_data', '=', $mes)
                    ->whereYear('inicio_data', '=', $ano)
                    ->count();

                $array['sessoes_por_usuario_e_por_tipo_terapia']['terapias']['Massagem nos Pés'][] = Terapias_MassagemPes::where('cancelado', false)
                    ->where('usuario', $id)
                    ->whereMonth('inicio_data', '=', $mes)
                    ->whereYear('inicio_data', '=', $ano)
                    ->count();

            }

            unset($array['sessoes_por_usuario_e_por_tipo_terapia']['ids']);

            foreach($array['sessoes_por_usuario_e_por_tipo_terapia']['users'] as $key => $user){
                $array['sessoes_por_usuario_e_por_tipo_terapia']['users'][] = substr($user, 0, strpos($user, " ") + 2).".";
                unset($array['sessoes_por_usuario_e_por_tipo_terapia']['users'][$key]);
            }
            
            //dd($array);

            return \Response::json($array);

        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function enviar_lista_sessoes_dia_email(Request $request){

        if(Auth::user()->is_admin_recepcao()){

            $validatedData = Validator::make($request->all(), [
                'tipo' => 'required|in:qm,at,mp,mr',
                'email' => 'required|email',
            ]);
            if (!$validatedData->fails()){

                $sessoes_agendadas;

                if($request->tipo == "qm"){
                    //QUICK MASSAGE
                    $terapia_qm = $this->terapia_quick_massage;
                    $quick_massage = new Terapia($terapia_qm);
                    $sessoes_agendadas = $quick_massage->buildArray();
                    foreach($sessoes_agendadas['dias'] as $key => $dia){
                        if(!Carbon::parse($dia['dia'])->isToday()){
                            unset($sessoes_agendadas['dias'][$key]);
                        }
                    }
                    $sessoes_agendadas['tempo_sessao'] = $terapia_qm['tempo_sessao'];
                    $sessoes_agendadas['tipo'] = "Quick Massage";
                }else if($request->tipo == "at"){
                    //AURICULOTERAPIA
                    $terapia_at = $this->terapia_auriculoterapia;
                    $auriculoterapia = new Terapia($terapia_at);
                    $sessoes_agendadas = $auriculoterapia->buildArray();
                    foreach($sessoes_agendadas['dias'] as $key => $dia){
                        if(!Carbon::parse($dia['dia'])->isToday()){
                            unset($sessoes_agendadas['dias'][$key]);
                        }
                    }
                    $sessoes_agendadas['tempo_sessao'] = $terapia_at['tempo_sessao'];
                    $sessoes_agendadas['tipo'] = "Auriculo Terapia";
                }else if($request->tipo == "mp"){
                    //MASSAGEM NOS PÉS
                    $terapia_mp = $this->terapia_massagem_pes;
                    $massagem_pes = new Terapia($terapia_mp);
                    $sessoes_agendadas = $massagem_pes->buildArray();
                    foreach($sessoes_agendadas['dias'] as $key => $dia){
                        if(!Carbon::parse($dia['dia'])->isToday()){
                            unset($sessoes_agendadas['dias'][$key]);
                        }
                    }
                    $sessoes_agendadas['tempo_sessao'] = $terapia_mp['tempo_sessao'];
                    $sessoes_agendadas['tipo'] = "Massagem nos Pés";
                }else if($request->tipo == "mr"){
                    //TODO
                }
                Mail::send('emails.terapias_lista', ['content' => $sessoes_agendadas], function ($message) use ($request, $sessoes_agendadas){
                    $message->from('intranet@chebabi.adv.br', 'Chebabi - Izique Chebabi Advogados');
                    $message->to($request->email, $name = null);
                    $message->subject('Agendamentos ' .$sessoes_agendadas['tipo']. ' - '.Carbon::today()->format('d/m/Y'));
                });

                $request->session()->flash('alert-success', 'Lista enviada para o e-mail informado!');
                return redirect()->action('Terapias\TerapiasController@painel_admin');
            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }

        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function incluir_dia_sem_terapia(Request $request){

        if(Auth::user()->is_admin_recepcao()){

            $validatedData = Validator::make($request->all(), [
                'data' => 'required|date_format:Y-m-d|after_or_equal:today',
                'tipo' => 'required|in:quick_massages,auriculoterapias,massagens_pes,massagens_relaxantes,mat_pilates',
            ]);
            if (!$validatedData->fails()){

                $sessoes_agendadas = DB::table('terapias_'.$request->tipo)
                                    ->where('cancelado', false)
                                    ->whereDate('inicio_data', '=', $request->data)
                                    ->get();
                
                foreach($sessoes_agendadas as $sessao){
                    if(!Terapia::cancelar_sessao_by_id($request->tipo, $sessao->id)){
                        return redirect()->back()->withErrors(array('message' => 'Erro ao incluir. Não foi possível cancelar as sessões já agendadas nesta data!'));
                    }
                }
    
                DB::table('terapias_dias_sem_terapias')->insert([
                    'data' => $request->data,
                    'tipo' => $request->tipo,
                    'usuario' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
    
                $request->session()->flash('alert-success', 'Data incluida com sucesso!');
                return redirect()->action('Terapias\TerapiasController@painel_admin');
            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }


        }else{
            return abort(403, 'Não autorizado.');
        }
    }
    

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

        /////////CANCELAR SESSÕES JÁ AGENDADAS, SE HOUVEREM

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
