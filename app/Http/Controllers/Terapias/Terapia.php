<?php

namespace Intranet\Http\Controllers\Terapias;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intranet\Terapias_QuickMassage;
use Intranet\Terapias_MassagemPes;
use Intranet\Terapias_Auriculoterapia;
use Intranet\Terapias_MassagemRelaxante;
use Intranet\Terapias_MatPilates;
use Intranet\Http\Controllers\APIs\MicrosoftController;
use Validator;

class Terapia{
    /*
    Dias>horarios   false = livre, nome user = ocupado pelo user de mesmo nome
    Limite_diario   se user atingou o limite diário
    Limite_mensal   se user atingou o limite mensal
    Dias>disponivel      se haverá sessões nesse dia. As vezes cancelam
    Exemplo array final:
    array:2 [▼
        "dias" => array:5 [▼
            6 => array:6 [▼
            "limite_mensal" => false
            "dia" => "2019-09-06"
            "dia_feira" => "Segunda-feira"
            "disponivel" => true
            "limite_diario" => "quick-massage"
            "limite_intervalo_agendamento" => false
            "horarios" => array:11 [▼
                "15:20" => false
                "15:35" => false
                "15:50" => false
                "16:05" => "Victor Meireles"
                "16:20" => false
                ]
            ]
        ]
    ]
    */
     /**
     * Array com os parâmetros para construção e processamento do array abaixo.
     *
     * @var array
     * 
     * @param string $terapia['tipo']
     * @param int $terapia['tempo_sessao']
     * @param int/bool $terapia['intervalo_agendamento']
     * @param array $terapia['dias_horarios']
     */
    private $terapia;
     /**
     * Array a ser construido, retornado e renderizado na view.
     *
     * @var array
     */
    private $array;

    function __construct($terapia){
        $this->array = array(
            'dias' => array(),
        );
        $this->terapia = $terapia;
    }

    public static function validator(array $data){
        return Validator::make($data, [
            'data' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s',
            // TODO - checar se hora não é passado
        ]);
    }

    public function buildArray(){
        /**
         * Monta array que será renderizado na view ref. terapia baseado no array $dias_horarios.
         *
         * @param array $terapia
         * @return array
         */
        /* Exemplo parâmetros
        $this->terapia['tipo'] = "quick_massages"; //nome da tabela ref. terapia sem o prefixo "terapias_"
        $this->terapia['dias'][]['limite_mensal'] = 4; //sessões/mês
        $this->terapia['tempo_sessao'] = 15; //minutos
        $this->terapia['intervalo_agendamento'] = false; //Se houver intervalo além do diário e mensal. Ex: Auriculoterapia só pode repetir depois de 7 dias
        $this->terapia['dias_horarios'] = array( //domingo = 1, segunda = 2, terça = 3, ...
            '2' => array(
                'inicio' => '14:00',
                'fim' => '15:45',
                'intervalo' => false, //SE HOUVER INTERVALO: 'intervalo' => array('inicio' => 'HH:MM','fim' => 'HH:MM',),
            ),
            '3' => array(
                'inicio' => '14:00',
                'fim' => '14:45',
                'intervalo' => false,
        );
        */
        //Setando os dias da semana definidos no array $dias_horarios e setando os demais campos padrão
        $i = 0;
        foreach($this->terapia['dias_horarios'] as $key => $dia){
            if($key == '2'){ //segunda
                $this->array['dias'][$i]['dia'] = Carbon::today()->startOfWeek()->format('Y-m-d');
                $this->array['dias'][$i]['dia_feira'] = "Segunda-feira";
                $this->array['dias'][$i]['horarios'] = array("inicio" => $dia["inicio"], "fim" => $dia["fim"]);
                $this->array['dias'][$i]['horarios']['intervalo'] = $dia["intervalo"] ? $dia["intervalo"] : false;
                $i++;
            }else if($key == '3'){ //terça
                $this->array['dias'][$i]['dia'] = Carbon::today()->startOfWeek()->addDay()->format('Y-m-d');
                $this->array['dias'][$i]['dia_feira'] = "Terça-feira";
                $this->array['dias'][$i]['horarios'] = array("inicio" => $dia["inicio"], "fim" => $dia["fim"]);
                $this->array['dias'][$i]['horarios']['intervalo'] = $dia["intervalo"] ? $dia["intervalo"] : false;
                $i++;
            }else if($key == '4'){ //quarta
                $this->array['dias'][$i]['dia'] = Carbon::today()->startOfWeek()->addDays(2)->format('Y-m-d');
                $this->array['dias'][$i]['dia_feira'] = "Quarta-feira";
                $this->array['dias'][$i]['horarios'] = array("inicio" => $dia["inicio"], "fim" => $dia["fim"]);
                $this->array['dias'][$i]['horarios']['intervalo'] = $dia["intervalo"] ? $dia["intervalo"] : false;
                $i++;
            }else if($key == '5'){ //quinta
                $this->array['dias'][$i]['dia'] = Carbon::today()->startOfWeek()->addDays(3)->format('Y-m-d');
                $this->array['dias'][$i]['dia_feira'] = "Quinta-feira";
                $this->array['dias'][$i]['horarios'] = array("inicio" => $dia["inicio"], "fim" => $dia["fim"]);
                $this->array['dias'][$i]['horarios']['intervalo'] = $dia["intervalo"] ? $dia["intervalo"] : false;
                $i++;
            }else if($key == '6'){ //sexta
                $this->array['dias'][$i]['dia'] = Carbon::today()->startOfWeek()->addDays(4)->format('Y-m-d');
                $this->array['dias'][$i]['dia_feira'] = "Sexta-feira";
                $this->array['dias'][$i]['horarios'] = array("inicio" => $dia["inicio"], "fim" => $dia["fim"]);
                $this->array['dias'][$i]['horarios']['intervalo'] = $dia["intervalo"] ? $dia["intervalo"] : false;
                $i++;
            }
        }
        //Setando demais campos padrão
        foreach($this->array['dias'] as $key => $dia){
            $this->array['dias'][$key]['disponivel'] = true;
            $this->array['dias'][$key]['limite_diario'] = false;
            $this->array['dias'][$key]['limite_mensal'] = false;
            $this->array['dias'][$key]['limite_intervalo_agendamento'] = false;
        }
        
        //Adicionando mais dias no final do array, conforme determinado pela Aline
        $limite = 14;
        for($i = 0; $i < $limite - count($this->array['dias']); $i++){
            $this->array['dias'][] = $this->array['dias'][$i];
            $this->array['dias'][count($this->array['dias'])-1]['dia'] = Carbon::parse($this->array['dias'][count($this->array['dias'])-1]['dia'])->addDays(7)->format('Y-m-d');
        }
        //Unset nos dias que já passaram
        foreach($this->array['dias'] as $key => $dia){
            if(Carbon::parse($dia['dia']) < Carbon::today()){
                unset($this->array['dias'][$key]);
            }
        }
        //Limitando os futuros
        $dias_count = $this->terapia['tipo'] == "massagens_pes" ? 3 : 6;
        while(count($this->array['dias']) > $dias_count){
            array_pop($this->array['dias']);
        }
        $this->array['dias'] = array_values($this->array['dias']);
        //Checando se há dias sem terapias nas datas fixadas no $this->array
        //Checando limite diário usuário
        //Checando limite mensal usuário para cada dia - o array pode conter dias de meses diferentes
        foreach($this->array['dias'] as $key => $dia){
            $terapias_usuario_dia = $this->terapias_usuario_dia($dia['dia']);
            $dia_sem_terapia = $this->dia_sem_terapia($dia['dia'],$this->terapia['tipo']);
            $terapias_usuario_mes = $this->terapias_usuario_mes($this->terapia['tipo'],$dia['dia']);
            if($terapias_usuario_dia){
                $this->array['dias'][$key]['limite_diario'] = $terapias_usuario_dia;
            }
            if($dia_sem_terapia){
                $this->array['dias'][$key]['disponivel'] = false;
            }
            if($terapias_usuario_mes >= $this->terapia['limite_mensal']){
                $this->array['dias'][$key]['limite_mensal'] = true;
            }
        }
        //Checando limite intervalo agendamento
        if($this->terapia['intervalo_agendamento']){
            foreach($this->array['dias'] as $key => $dia){
                $this->array['dias'][$key]['limite_intervalo_agendamento'] = $this->limite_intervalo_agendamento($this->terapia['tipo'],$this->terapia['intervalo_agendamento'],$dia['dia']);
            } 
        }
        //Setando horários nos dias
        $horarios = $this->set_horarios_dias($this->terapia['tempo_sessao']);
        //Checando sessões já agendadas
        foreach($this->array['dias'] as $key => $dia){
            $terapias_agendadas = $this->terapias_agendadas($this->terapia['tipo'],$dia['dia']);
            foreach($terapias_agendadas as $agendamento){
                if(array_key_exists(Carbon::parse($agendamento->inicio_hora)->format('H:i'), $this->array['dias'][$key]['horarios'])){
                    $this->array['dias'][$key]['horarios'][Carbon::parse($agendamento->inicio_hora)->format('H:i')] = $agendamento->user->name;
                }
            }
        }
        //ADD IF $TIPO PARA FUNÇÕES EXCLUSIVAS RELAXANTE E MAT PILATES
        return $this->array;
    }
    ////////////////////////////////////////////////////////////////////////
    public static function dia_sem_terapia($dia,$tipo){
        /**
         * Checa se haverá sessões da terapia $tipo no $dia.
         *
         * @param string $tipo
         * @param string $dia
         * @return int;
         */
        $dia_sem_terapia = DB::table('terapias_dias_sem_terapias')
            ->where('tipo', $tipo)
            ->whereDate('data', Carbon::parse($dia)->format('Y-m-d'))
            ->count();

        return $dia_sem_terapia;
    }

    public static function terapias_usuario_mes($tipo,$dia){
        /**
         * Checa se o usuário tem alguma terapia agendada do $tipo no mês corrente.
         *
         * @param string $tipo
         * @return int
         */
        $terapias_usuario_mes = DB::table('terapias_'.$tipo)
            ->where('cancelado', false)
            ->where('usuario', Auth::user()->id)
            ->where('livre_bonus', false)
            ->whereMonth('inicio_data', Carbon::parse($dia)->month)
            ->whereYear('inicio_data', Carbon::parse($dia)->year)
            ->count();
        return $terapias_usuario_mes;
    }

    public static function terapias_usuario_dia($dia){
         /**
         * Checa se o usuário tem qualquer terapia agendada no dia, exceto MAT Pilates
         * Como o limite de agendamento por usuário também é de uma terapia por dia,
         * Se houver qualquer agendamento conforme acima, o usuário terá batido o limite.
         *
         * @param string $dia
         * @return string/bool
         */
        $terapias_usuario_dia;
        $tipos = array('quick_massages', 'auriculoterapias', 'massagens_pes', 'massagens_relaxantes');
        foreach($tipos as $tipo){
            $terapias_usuario_dia = DB::table('terapias_'.$tipo)
            ->where('cancelado', false)
            ->where('usuario', Auth::user()->id)
            //->where('livre_bonus', false) //Se houver uma sessão já agendada como bonus, o sistema deixava agendar outra. Limite de 1 por dia.
            ->whereDate('inicio_data', $dia)
            ->count();
            if($terapias_usuario_dia > 0){
                if($tipo == "quick_massages"){
                    return "Quick Massage";
                }else if($tipo == "auriculoterapias"){
                    return "Auriculoterapia";
                }else if($tipo == "massagens_pes"){
                    return "Massagens nos Pés";
                }else if($tipo == "massagens_relaxantes"){
                    return "Massagem Relaxante";
                }
            }
        }
        return false;
    }

    public static function limite_intervalo_agendamento($tipo,$intervalo_agendamento,$dia){
        /**
         * Checa se o usuário está dentro do intervalo mínimo entre duas sessões
         * de terapia do $tipo. Quick Massage = "semanal", Auriculo = 7; //dias
         *
         * @param string $tipo
         * @param string/int $intervalo_agendamento
         * @param string $dia
         * @return int/bool
         */
        $limite_intervalo_agendamento;
        if(gettype($intervalo_agendamento) == "string"){ //semanal, mensal, etc
            if($intervalo_agendamento == "semanal"){
                $limite_intervalo_agendamento = DB::table('terapias_'.$tipo)
                    ->where('cancelado', false)
                    ->where('usuario', Auth::user()->id)
                    ->where('livre_bonus', false)
                    ->whereBetween('inicio_data', [Carbon::parse($dia)->startOfWeek()->format('Y-m-d'), Carbon::parse($dia)->endOfWeek()->format('Y-m-d')])
                    ->count();
                return $limite_intervalo_agendamento;
            }//SE SURGIR OUTROS LIMITADORES ALÉM DO "semanal", TRATAR COM MAIS UM ELSE IF AQUI
        }else if(gettype($intervalo_agendamento) == "integer"){ //número de dias
            $limite_intervalo_agendamento = DB::table('terapias_'.$tipo)
                ->where('cancelado', false)
                ->where('usuario', Auth::user()->id)
                ->where('livre_bonus', false)
                ->whereBetween('inicio_data', [Carbon::parse($dia)->subDays($intervalo_agendamento-1)->format('Y-m-d'), Carbon::parse($dia)->addDays($intervalo_agendamento-1)->format('Y-m-d')])
                ->count();
            return $limite_intervalo_agendamento;
        }
    }

    private function set_horarios_dias($tempo_sessao){
        /**
         * Seta on horário em cada dia no $self::array baseado nos parâmetros
         * "inicio", "fim" e "intervalo" passados pelo array $dias_horarios
         *
         * @param integer $tempo_sessao
         */
        $horarios = array();
        foreach($this->array['dias'] as $key => $dia){
            $horarios += array($key => array($dia['horarios']['inicio'] => false));
            $sessao = $dia['horarios']['inicio'];

            while(Carbon::parse($sessao)->format('H:i') < Carbon::parse($dia['horarios']['fim'])->format('H:i')){
                if($dia['horarios']['intervalo'] && !Carbon::parse($sessao)->addMinutes($tempo_sessao)->between(Carbon::parse($dia['horarios']['intervalo']['inicio'])->subMinute(), Carbon::parse($dia['horarios']['intervalo']['fim'])->subMinute())){
                    $horarios[$key] += array(Carbon::parse($sessao)->addMinutes($tempo_sessao)->format('H:i') => false);
                }else if(!$dia['horarios']['intervalo']){
                    $horarios[$key] += array(Carbon::parse($sessao)->addMinutes($tempo_sessao)->format('H:i') => false);
                }
                $sessao = Carbon::parse($sessao)->addMinutes($tempo_sessao)->format('H:i');
            }
            $this->array['dias'][$key]['horarios'] += $horarios[$key];
            unset($this->array['dias'][$key]['horarios']['inicio']);
            unset($this->array['dias'][$key]['horarios']['fim']);
            unset($this->array['dias'][$key]['horarios']['intervalo']);
        }
    }
    
    public static function terapias_agendadas($tipo,$dia){
        /**
         * Checa as sessões já agendadas da terapia $tipo no $dia
         *
         * @param string $tipo
         * @param string $dia
         * @return array
         */
        $terapias_agendadas;
        if($tipo == "quick_massages"){
            $terapias_agendadas = Terapias_QuickMassage::with('user:id,name');
        }else if($tipo == "auriculoterapias"){
            $terapias_agendadas = Terapias_Auriculoterapia::with('user:id,name');
        }else if($tipo == "massagens_pes"){
            $terapias_agendadas = Terapias_MassagemPes::with('user:id,name');
        }else if($tipo == "massagens_relaxantes"){
            $terapias_agendadas = Terapias_MassagemRelaxante::with('user:id,name');
        }
        $terapias_agendadas->where('cancelado', false)
            ->whereDate('inicio_data', $dia);
        return $terapias_agendadas->get();
    }

    public static function sessao_livre($tipo,$dia,$horario_inicio){
        /**
         * Checa se a sessão do $dia e $horario_inicio da terapia $tipo está livre
         *
         * @param string $tipo
         * @param string $dia
         * @param string $horario_inicio
         * @return boolean
         */
        $sessao = DB::table('terapias_'.$tipo)
            ->where('cancelado', false)
            ->whereDate('inicio_data', $dia)
            ->whereTime('inicio_hora', '=', $horario_inicio)
            ->count();
        if($sessao){
            return false;
        }else{
            return true;
        }
    }

    public static function livre_bonus($dia,$horario_inicio,$limite_min){
        /**
         * Checa se a sessão do $dia e $horario_inicio pode ser agendada
         * como bônus por estar livre faltando $limite_min para o início da mesma
         *
         * @param string $dia
         * @param string $horario_inicio
         * @param string $limite_min
         * @return boolean
         */
        return Carbon::now()->between(Carbon::parse($dia." ".$horario_inicio)->subMinutes($limite_min), Carbon::parse($dia." ".$horario_inicio));
    }

    public static function agendar_sessao($tipo,$dia,$horario_inicio,$tempo_sessao,$livre_bonus,$lembrete_minutos_antes_inicio){
        /**
         * Agenda uma sessão de terapia $tipo no $dia e $horario_inicio em nome de Auth::user();
         *
         * @param string $tipo
         * @param string $dia
         * @param string $horario_inicio
         * @param int $tempo_sessao
         * @param boolean $livre_bonus
         * @param int lembrete_minutos_antes_inicio
         * @return string/array
         */
        require_once ('evento_terapia_json.php');
        $evento = json_encode($evento);
        $url = 'https://graph.microsoft.com/v1.0/users/terapias@chebabi.com/events';
        $resultado = MicrosoftController::curl($url, $evento, "POST"); // cURL
        if(empty($resultado["error"])){
            $id = DB::table('terapias_'.$tipo)->insertGetId([
                'evento_id' => $resultado['id'],
                'usuario' => Auth::user()->id,
                'inicio_data' => Carbon::parse($resultado['start']['dateTime'])->format('Y-m-d'),
                'inicio_hora' => Carbon::parse($resultado['start']['dateTime'])->format('H:i:s'),
                'fim_data' => Carbon::parse($resultado['end']['dateTime'])->format('Y-m-d'),
                'fim_hora' => Carbon::parse($resultado['end']['dateTime'])->format('H:i:s'),
                'cancelado' => false,
                'livre_bonus' => $livre_bonus,
                'created_at' => Carbon::now(),
            ]);
            if(empty($id)){
                return array("error" => "Erro ao salvar no Banco de Dados!");
            }else{
                return "agendado";
            }
        }else{
            return $resultado;
        }
    }

    public static function cancelar_sessao($tipo,$dia,$horario_inicio){
        /**
         * Cancela uma sessão de terapia $tipo no $dia e $horario_inicio em nome de Auth::user();
         *
         * @param string $tipo
         * @param string $dia
         * @param string $horario_inicio
         * @return string/array
         */

        $sessao = DB::table('terapias_'.$tipo)
            ->where('cancelado', false)
            ->where('usuario', Auth::user()->id)
            ->whereDate('inicio_data', $dia)
            ->whereTime('inicio_hora', '=', $horario_inicio)
            ->first();

        if(!is_null($sessao)){
            $comentario_evento = json_encode(array('Comment' => 'Sessão cancelada.'));
            $url = 'https://graph.microsoft.com/beta/users/terapias@chebabi.com/events/'.$sessao->evento_id.'/cancel';
            $resultado = MicrosoftController::curl($url, $comentario_evento, "POST"); // cURL
            if(empty($resultado["error"])){
                DB::table('terapias_'.$tipo)->where('id', $sessao->id)->update(['cancelado' => true, 'updated_at' => Carbon::now()]);
                return "cancelado";
                /* TODO
                if(condição para checar se o registro foi alterado (só encontrei com instância de objeto ->save())){
                    return "cancelado";
                }else{
                    return array("error" => "Erro ao salvar no Banco de Dados!");
                }
                */
            }else{
                return $resultado;
            }
        }else{
            return array("error" => "Sessão não encontrada ou usuário sem permissão!");
        }
    }

    public static function cancelar_sessao_by_id($tipo,$id){
        /**
         * Cancela uma sessão de terapia do $tipo com $id;
         *
         * @param string $tipo
         * @param integer $id
         * @return boolean
         */

        $sessao = DB::table('terapias_'.$tipo)->find($id);

        if(!is_null($sessao)){
            $comentario_evento = json_encode(array('Comment' => 'Sessão cancelada. Nesse dia não haverá sessões deste tipo de terapia.'));
            $url = 'https://graph.microsoft.com/beta/users/terapias@chebabi.com/events/'.$sessao->evento_id.'/cancel';
            $resultado = MicrosoftController::curl($url, $comentario_evento, "POST"); // cURL
            if(empty($resultado["error"])){
                DB::table('terapias_'.$tipo)->where('id', $sessao->id)->update(['cancelado' => true, 'updated_at' => Carbon::now()]);
                return true;
                /* TODO
                if(condição para checar se o registro foi alterado (só encontrei com instância de objeto ->save())){
                    return "cancelado";
                }else{
                    return array("error" => "Erro ao salvar no Banco de Dados!");
                }
                */
            }
        }
        
        return false;
    }

}
