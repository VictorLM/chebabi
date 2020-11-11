<?php

namespace Intranet\Http\Controllers\ReservasEstacoes;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use Intranet\ReservaEstacao;

class ReservasEstacoesController extends Controller {

    public function __construct(){
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        $title = 'Reservas Estações | Intranet Izique Chebabi Advogados Associados';
        if($request->has('date')){
            // VALIDAR DATA
            $validatedData = Validator::make($request->all(), [
                'date' => 'required|date|after_or_equal:today|before:' . Carbon::parse(Carbon::now())->addYear()->format('Y-m-d'),
            ]);
            if(!$validatedData->fails()){
                $dia = $request->date;
                if(ReservaEstacao::tem_reserva($dia)){
                    // IF JÁ TEM RESERVA NESSA DATA - RENDER PÁGINA MINHAS RESERVAS
                    $request->session()->flash('alert-warning', 'Você já tem uma reserva nesta data.');
                    return redirect('/intranet/reservar/minhas-reservas');
                }else{
                    // RETURN RENDER FORM P/ RESERVAR COM A DATA SELECIONADA
                    $estacoes_livres_dia_count = ReservaEstacao::estacoes_livres_dia_count($dia);
                    $estacionamentos_livres_dia_count = ReservaEstacao::estacionamentos_livres_dia_count($dia);
                    return view('reservas.reservar', compact('title', 'dia', 'estacoes_livres_dia_count', 'estacionamentos_livres_dia_count'));
                }
            }else{
                return redirect()->back()->withErrors($validatedData)->withInput();
            }
        }else{
            return view('reservas.select_date', compact('title'));
        }
    }

    public function reservar(Request $request){
        // VALIDAR
        $validatedData = Validator::make($request->all(), [
            'inicio' => 'required|date|after_or_equal:today|before:' . Carbon::parse(Carbon::now())->addYear()->format('Y-m-d'),
            'fim' => 'required|date|after_or_equal:inicio|before:' . Carbon::parse(Carbon::now())->addYear()->format('Y-m-d'),
            'estacionamento' => 'required|boolean',
        ]);
        if (!$validatedData->fails()){
            $reserva = new ReservaEstacao();
            // Fill
            $reserva->fill($request->all());
            $reserva->user = Auth::user()->id;
            //
            $validar_nova_reserva = $reserva->validar_nova_reserva();
            if($validar_nova_reserva === "ok"){
                $reserva->save();
                $request->session()->flash('alert-success', "Reserva feita com sucesso! <a href='/intranet/reservar/minhas-reservas'>Ver minhas reservas</a>.");
                return redirect()->action('ReservasEstacoes\ReservasEstacoesController@orientacoes_covid');
            }else{
                $request->session()->flash('alert-warning', $validar_nova_reserva);
                return redirect()->back()->withInput();
            }
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }

    public function minhas_reservas(Request $request){
        $reservas_user = ReservaEstacao::reservas_user();
        $title = 'Minhas Reservas | Intranet Izique Chebabi Advogados Associados';
        return view('reservas.minhas_reservas', compact('title', 'reservas_user'));
    }
    /*
    public function editar_form(Request $request, $id){
        $reserva = ReservaEstacao::find($id);
        if($reserva){
            if($reserva->user === Auth::user()->id){
                $title = 'Editar Reserva | Intranet Izique Chebabi Advogados Associados';
                return view('reservas.edit', compact('title', 'reserva'));
            }else{
                $request->session()->flash('alert-warning', 'Você não está autorizado a editar esta reserva.');
            }
        }else{
            $request->session()->flash('alert-warning', 'Reserva não encontrada.');
        }
        return redirect()->action('ReservasEstacoes\ReservasEstacoesController@minhas_reservas');
    }
    
    public function editar(Request $request, $id){
        $reserva = ReservaEstacao::find($id);
        if($reserva){
            if($reserva->user === Auth::user()->id){
                // VALIDAR
                $validatedData = Validator::make($request->all(), [
                    'inicio' => 'required|date|date_equals:' . $reserva->inicio,
                    'fim' => 'required|date|after_or_equal:inicio|before:' . Carbon::parse($reserva->inicio)->addYear()->format('Y-m-d'),
                    'estacionamento' => 'required|boolean',
                ]);
                if (!$validatedData->fails()){
                    // Fill
                    $reserva->fill($request->all());
                    //
                    $validar_edicao_reserva = $reserva->validar_edicao_reserva();
                    if($validar_edicao_reserva === 'ok'){ //VALIDACAO
                        $reserva->save();
                        $request->session()->flash('alert-success', 'Reserva atualizada com sucesso!');
                    }else{
                        $request->session()->flash('alert-warning', $validar_nova_reserva);
                        return redirect()->back()->withInput();
                    }
                }else{
                    return redirect()->back()->withErrors($validatedData)->withInput();
                }
            }else{
                $request->session()->flash('alert-warning', 'Você não está autorizado a cancelar esta reserva.');
            }
        }else{
            $request->session()->flash('alert-warning', 'Reserva não encontrada.');
        }
        return redirect()->action('ReservasEstacoes\ReservasEstacoesController@minhas_reservas');
    }*/
    public function cancelar(Request $request, $id){
        $reserva = ReservaEstacao::find($id);
        if($reserva){
            if($reserva->user === Auth::user()->id){
                if(Carbon::parse($reserva->fim) <= Carbon::today()){
                    $reserva->cancelado = Carbon::now();
                    $reserva->save();
                    $request->session()->flash('alert-success', 'Reserva cancelada com sucesso!');
                }else{
                    $request->session()->flash('alert-warning', 'Não é possível cancelar reservas já passadas.');
                }
            }else{
                $request->session()->flash('alert-warning', 'Você não está autorizado a cancelar esta reserva.');
            }
        }else{
            $request->session()->flash('alert-warning', 'Reserva não encontrada.');
        }
        return redirect()->action('ReservasEstacoes\ReservasEstacoesController@minhas_reservas');
    }

    public function todas_reservas(Request $request){
        if(Auth::user()->is_admin_recepcao()){
            $date = Carbon::today();
            if($request->has('date')){
                // VALIDAR DATA
                $validatedData = Validator::make($request->all(), [
                    'date' => 'required|date',
                ]);
                if(!$validatedData->fails()){
                    $date = $request->date;
                }else{
                    return redirect()->back()->withErrors($validatedData)->withInput();
                }
            }   
            $reservas = ReservaEstacao::with('usuario:id,name')
                        ->whereDate('inicio', '<=', $date)
                        ->whereDate('fim', '>=', $date)
                        ->where('cancelado', null)
                        ->orderby('inicio', 'ASC')
                        ->get();
            $vagas_total_dia = ReservaEstacao::vagas_total_dia();
            $title = 'Reservas Estações | Intranet Izique Chebabi Advogados Associados';
            return view('reservas.todas_reservas', compact('title', 'vagas_total_dia', 'reservas', 'date'));
        }else{
            $request->session()->flash('alert-warning', 'Você não tem permissão para acessar esta página.');
            return redirect('/intranet');
        }
    }

    public function orientacoes_covid(Request $request){
        $title = 'Orientações COVID-19 | Intranet Izique Chebabi Advogados Associados';
        return view('reservas.orientacoes_covid', compact('title'));
    }
    
}
