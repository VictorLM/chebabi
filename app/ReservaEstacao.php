<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservaEstacao extends Model {

    protected $table = 'reservas_estacoes';

    protected $guarded = [];

    public function usuario(){
        return $this->hasOne('Intranet\User', 'id', 'user');
    }

    // TODO - TRY CATCH - RIP TRY CATCH
    /*
    try {
        
        return true;
    } catch(Exception $e) {
        return false;
    } 
    */

    public static function vagas_total_dia() {
        // RETORNA A QUANTIDADE TOTAL DE VAGAS PARA ESTAÇÕES E ESTACIONAMENTO
        $vagas = DB::table('vagas_estacoes_estacionamento')->first();
        $vagas_dia = array('estacoes' => $vagas->estacoes, 'estacionamento' => $vagas->estacionamento);
        return $vagas_dia;
    }
    /*
    public static function reservas_dia($dia) {
        $reservas = DB::table('reservas_estacoes')
                    ->whereDate('inicio', '<=', $dia)
                    ->whereDate('fim', '>=', $dia)
                    ->where('cancelado', null)
                    ->get();
        return $reservas;
    }
    */
    public static function reservas_user() {
        $reservas = DB::table('reservas_estacoes')
                    ->whereDate('fim', '>=', Carbon::today())
                    ->where('cancelado', null)
                    ->where('user', Auth::user()->id)
                    ->orderby('inicio', 'ASC')
                    ->get();
        return $reservas;
    }

    public static function reservas_dia_estacoes_count($dia) {
        $count = DB::table('reservas_estacoes')
                ->whereDate('inicio', '<=', $dia)
                ->whereDate('fim', '>=', $dia)
                ->where('cancelado', null)
                ->count();
        return $count;
    }

    public static function reservas_dia_estacionamento_count($dia) {
        $count = DB::table('reservas_estacoes')
                ->whereDate('inicio', '<=', $dia)
                ->whereDate('fim', '>=', $dia)
                ->where('cancelado', null)
                ->where('estacionamento', true)
                ->count();
        return $count;
    }

    public static function tem_reserva($dia) {
        // CHECA SE O USER JÁ TEM RESERVA NO $DIA
        $count = DB::table('reservas_estacoes')
                ->whereDate('inicio', '<=', $dia)
                ->whereDate('fim', '>=', $dia)
                ->where('cancelado', null)
                ->where('user', Auth::user()->id)
                ->count();
        if($count > 0){
            return true;
        }
        return false;
    }

    public static function estacoes_livres_dia_count($dia) {
        $vagas_estacoes_total_dia = ReservaEstacao::vagas_total_dia()['estacoes'];
        $reservas_dia_estacoes_count = ReservaEstacao::reservas_dia_estacoes_count($dia);
        $estacoes_livres_dia_count = $vagas_estacoes_total_dia - $reservas_dia_estacoes_count;
        return $estacoes_livres_dia_count;
    }

    public static function estacionamentos_livres_dia_count($dia) {
        $vagas_estacionamento_total_dia = ReservaEstacao::vagas_total_dia()['estacionamento'];
        $reservas_dia_estacionamento_count = ReservaEstacao::reservas_dia_estacionamento_count($dia);
        $estacionamentos_livres_dia_count = $vagas_estacionamento_total_dia - $reservas_dia_estacionamento_count;
        return $estacionamentos_livres_dia_count;
    }

    public function validar_nova_reserva() { 
        $dates = [];
        // SE RECORRÊNCIA
        if($this->inicio !== $this->fim){
            $inicio = Carbon::parse($this->inicio);
            $fim = Carbon::parse($this->fim);
            while($inicio < $fim){
                $dates[] = $inicio->format('Y-m-d');
                $inicio->addDay();
            }
        }else{
        // SENÃO RECORRÊNCIA
            $dates[] = $this->inicio;
        }
        //
        foreach($dates as $date) {
            // IF USER JÁ TEM RESERVA DIA
            if($this->tem_reserva($date)){
                return "Você já tem uma reserva em " . Carbon::parse($date)->format('d/m/Y') . ". <a href='/intranet/reservar/minhas-reservas'>Ver minhas reservas</a>";
            }
            //
            $estacoes_livres_dia_count = $this->estacoes_livres_dia_count($date);
            // IF NÃO TEM ESTAÇÃO LIVRE DIA
            if($estacoes_livres_dia_count <= 0){
                return "Não há estação livre em " . Carbon::parse($date)->format('d/m/Y');
            }
            // IF ESTACIONAMENTO
            if($this->estacionamento){
                $estacionamentos_livres_dia_count = $this->estacionamentos_livres_dia_count($date);
                // IF NÃO TEM ESTACIONAMENTO LIVRE DIA
                if($estacionamentos_livres_dia_count <= 0){
                    return "Não há vaga de estacionamento livre em " . Carbon::parse($date)->format('d/m/Y');
                }
            }
        }
        return "ok";
    }

}
