<?php

namespace Intranet\Http\Controllers\Intranet;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Intranet\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Mail;
use Intranet\Uau;
use Intranet\Massagem;
use Validator;
use Intranet\Eventos;
use Intranet\Cliente;

class IntranetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $unread_uaus = DB::table('uaus')->where([
            ['para', Auth::user()->id],
            ['lido', '0'],
        ])->count();

        $last_login = DB::table('users')
            ->select('last_login')
            ->where('id', Auth::user()->id)
            ->first();
        
        if(!empty(Auth::user()->nascimento)
            && Carbon::parse(Auth::user()->nascimento)->format('d/m') == Carbon::parse(Carbon::today())->format('d/m')
            && Carbon::parse($last_login->last_login)->format('Y-m-d') != Carbon::parse(Carbon::today())->format('Y-m-d')){
            $aniversario = TRUE;
        }else{
            $aniversario = FALSE;
        }
        
        if(Auth::user()->tipo == 'admin'){
            $admin = TRUE;
        }else{
            $admin = FALSE;
        }

        $aniversariantes = DB::table('users')
            ->where('ativo', TRUE)
            ->whereMonth('nascimento', Carbon::today()->month)
            ->whereDay('nascimento', Carbon::today()->day)
            ->get();
            
        $aniversariantes = count($aniversariantes);
        
        $ranking_sorted = $this->ranking_uau();
        
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['last_login' => Carbon::now()]);
        
        $title = 'Intranet | Izique Chebabi Advogados Associados';
        
        return view('intranet.index', compact('title', 'unread_uaus', 'aniversario', 'aniversariantes','admin', 'ranking_sorted'));
    }

    public function sugestao(){
        return view('intranet.sugestao', compact('title'));
    }
    
    public function enviar_sugestao(Request $request){

        $validatedData = Validator::make($request->all(), [
            'sugestao' => 'required|string|max:2000',
        ]);

        if (!$validatedData->fails()){
            
            DB::table('sugestoes')->insert([
                'usuario' => Auth::user()->id,
                'sugestao' => strip_tags($request->sugestao),
                'infos_sessao' => 'IP: ' . \Request::ip() .' - USER-AGENT: ' . $request->header('User-Agent'),
                'created_at' => Carbon::now(),
            ]);
            
            $content = $request->all();
            
            Mail::send('emails.sugestao', ['content' => $content], function ($message) use ($content)
            {
                $message->from('postmaster@chebabi.adv.br', 'Sugestão - Izique Chebabi Advogados');
                $message->to('coordenacao@chebabi.com', $name = null);
                $message->cc('victor@chebabi.com', $name = null);
                $message->subject('Sugestão enviada pela intranet');
            });

            $request->session()->flash('alert-success', 'Sugestão enviada com sucesso! Obrigado por colaborar.');
            return redirect()->action('Intranet\IntranetController@index');
        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

    }

    public function agenda(){
        $title = 'Agenda | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.agenda', compact('title'));
    }

    public function eventos(){
        //PARA O AJAX DO FULL CALENDAR
        $eventos = Eventos::whereDate('start', '>=', Carbon::today()->subMonth())->get();
        //SÓ RETORNA OS EVENTOS DO ATÉ UM MÊS NO PASSADO
        for($i=0;$i<count($eventos);$i++){
            $eventos[$i]->dow = unserialize($eventos[$i]->dow);
            if(!empty($eventos[$i]->dow)){
                $eventos[$i]->start = Carbon::parse($eventos[$i]->start)->format('H:i:s');
                $eventos[$i]->end = Carbon::parse($eventos[$i]->end)->format('H:i:s');
            }
        }
        return \Response::json($eventos);
    }
    
    public function novo_evento(){
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->where('ativo', TRUE)
            ->where('id', '!=', Auth::user()->id)
            ->orderBy('name')
            ->get();
        $title = 'Novo Evento | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.novo_evento', compact('title', 'users'));
    }

    public function novo_evento_reuniao(){
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->where('ativo', TRUE)
            ->where('id', '!=', Auth::user()->id)
            ->orderBy('name')
            ->get();
        $title = 'Novo Evento Reunião | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.novo_evento_reuniao', compact('title', 'users'));
    }
    //AJAX PARA CHECAR DISPONIBILIDADE DAS SALAS DE REUNIÃO
    public function checagem_salas_reuniao(Request $request){
        if($request->has('sala') && $request->has('data') && Carbon::parse($request->data)->format('Y-m-d') >= Carbon::today()->toDateString()){
            if($request->sala == "Sala 1" || $request->sala == "Sala 2"){
                $eventos = Eventos::select("id","title","start","end","local")
                            ->where('cancelado', false)
                            ->where('tipo', 'Reunião')
                            ->where('local', $request->sala)
                            ->whereDate('start', $request->data)
                            ->orderBy('start')
                            ->get();
                return \Response::json($eventos);
            }else{
                return \Response::json(null);
            }
        }else{
            return \Response::json(null);
        }
    }
    
    public function showEvento(Request $request, $id){

        $evento = Eventos::find($id);

        if(Auth::user()->email == $evento->organizador_email || 
            Auth::user()->tipo == 'admin' || 
            Auth::user()->email == 'recepcao@chebabi.com' &&
            $evento->end > Carbon::now()){

            $edit_cancel = true;

        }else{
            $edit_cancel = false;
        }

        $title = 'Evento da Agenda | Intranet Izique Chebabi Advogados Associados';

        return view('intranet.evento', compact('title', 'evento', 'edit_cancel'));
    }

    public function editEvento($id){

        $evento = Eventos::find($id);

        if(!$evento->cancelado){
            $titulo = substr($evento->title, strpos($evento->title, " ") + 1);

            $users = DB::table('users')
                ->select('id', 'name', 'email')
                ->where('ativo', TRUE)
                ->where('email', '!=', $evento->organizador_email)
                ->orderBy('name')
                ->get();

            $envolvidos = array_column(unserialize($evento->envolvidos), 'emailAddress');
            $envolvidos = array_column($envolvidos, 'address');

            $title = 'Editar Evento | Intranet Izique Chebabi Advogados Associados';

            if(Auth::user()->email == $evento->organizador_email || 
                Auth::user()->tipo == 'admin' || 
                Auth::user()->email == 'recepcao@chebabi.com' &&
                $evento->end > Carbon::now()){

                if($evento->tipo == "Reunião"){
                    return view('intranet.editar_evento_reuniao', compact('title', 'evento', 'users', 'envolvidos', 'titulo'));
                    dd("TESTEEE");
                }else{
                    return view('intranet.editar_evento', compact('title', 'evento', 'users', 'envolvidos', 'titulo'));
                }

            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    public function agendamento_massagem(){

        $terca = array();
        $quarta = array();

        $segunda = Carbon::today()->startOfWeek();
        $terca['dia'] = $segunda->copy()->addDay();
        $quarta['dia'] = $terca['dia']->copy()->addDay();

        $terca['disponivel'] = $quarta['disponivel'] = true;

        $terca['horarios']['14:00'] = $quarta['horarios']['14:00'] = false;
        $terca['horarios']['14:10'] = $quarta['horarios']['14:10'] = false;
        $terca['horarios']['14:20'] = $quarta['horarios']['14:20'] = false;
        $terca['horarios']['14:30'] = $quarta['horarios']['14:30'] = false;
        $terca['horarios']['14:40'] = $quarta['horarios']['14:40'] = false;
        $terca['horarios']['14:50'] = $quarta['horarios']['14:50'] = false;
        $terca['horarios']['15:00'] = $quarta['horarios']['15:00'] = false;
        $terca['horarios']['15:10'] = $quarta['horarios']['15:10'] = false;
        $terca['horarios']['15:20'] = $quarta['horarios']['15:20'] = false;
        //15H30 ÀS 15H40 É A PAUSA DA MASSAGISTA
        $terca['horarios']['15:40'] = $quarta['horarios']['15:40'] = false;
        $terca['horarios']['15:50'] = $quarta['horarios']['15:50'] = false;
        $terca['horarios']['16:00'] = $quarta['horarios']['16:00'] = false;
        $terca['horarios']['16:10'] = $quarta['horarios']['16:10'] = false;
        $terca['horarios']['16:20'] = $quarta['horarios']['16:20'] = false;
        $terca['horarios']['16:30'] = $quarta['horarios']['16:30'] = false;
        $terca['horarios']['16:40'] = $quarta['horarios']['16:40'] = false;
        $terca['horarios']['16:50'] = $quarta['horarios']['16:50'] = false;
        $terca['horarios']['17:00'] = $quarta['horarios']['17:00'] = false;

        $terca['limite_usuario'] = $quarta['limite_usuario'] = false;

        $dias_sem_massagem = DB::table('dias_sem_massagens')->whereDate('data', '>=', $segunda)->get();
        
        foreach($dias_sem_massagem as $dia){
            if($dia->data == Carbon::parse($terca['dia'])->format('Y-m-d')){
                $terca['disponivel'] = false;
            }else if($dia->data == Carbon::parse($quarta['dia'])->format('Y-m-d')){
                $quarta['disponivel'] = false;
            }
        }

        $massagens_agendadas = Massagem::with('user:id,name')
            ->where('cancelado', false)
            ->whereDate('inicio_data', '>=' ,Carbon::parse($terca['dia'])->format('Y-m-d'))
            ->get();
        
        foreach($massagens_agendadas as $massagem){
            if($massagem->inicio_data == Carbon::parse($terca['dia'])->format('Y-m-d')){
                if($massagem->inicio_hora == '14:00:00'){
                    $terca['horarios']['14:00'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:10:00'){
                    $terca['horarios']['14:10'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:20:00'){
                    $terca['horarios']['14:20'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:30:00'){
                    $terca['horarios']['14:30'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:40:00'){
                    $terca['horarios']['14:40'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:50:00'){
                    $terca['horarios']['14:50'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:00:00'){
                    $terca['horarios']['15:00'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:10:00'){
                    $terca['horarios']['15:10'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:20:00'){
                    $terca['horarios']['15:20'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:40:00'){
                    $terca['horarios']['15:40'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:50:00'){
                    $terca['horarios']['15:50'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:00:00'){
                    $terca['horarios']['16:00'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:10:00'){
                    $terca['horarios']['16:10'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:20:00'){
                    $terca['horarios']['16:20'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:30:00'){
                    $terca['horarios']['16:30'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:40:00'){
                    $terca['horarios']['16:40'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:50:00'){
                    $terca['horarios']['16:50'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '17:00:00'){
                    $terca['horarios']['17:00'] = $massagem->user->name;
                }
            }else if($massagem->inicio_data == Carbon::parse($quarta['dia'])->format('Y-m-d')){
                if($massagem->inicio_hora == '14:00:00'){
                    $quarta['horarios']['14:00'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:10:00'){
                    $quarta['horarios']['14:10'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:20:00'){
                    $quarta['horarios']['14:20'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:30:00'){
                    $quarta['horarios']['14:30'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:40:00'){
                    $quarta['horarios']['14:40'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '14:50:00'){
                    $quarta['horarios']['14:50'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:00:00'){
                    $quarta['horarios']['15:00'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:10:00'){
                    $quarta['horarios']['15:10'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:20:00'){
                    $quarta['horarios']['15:20'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:40:00'){
                    $quarta['horarios']['15:40'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '15:50:00'){
                    $quarta['horarios']['15:50'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:00:00'){
                    $quarta['horarios']['16:00'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:10:00'){
                    $quarta['horarios']['16:10'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:20:00'){
                    $quarta['horarios']['16:20'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:30:00'){
                    $quarta['horarios']['16:30'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:40:00'){
                    $quarta['horarios']['16:40'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '16:50:00'){
                    $quarta['horarios']['16:50'] = $massagem->user->name;
                }else if($massagem->inicio_hora == '17:00:00'){
                    $quarta['horarios']['17:00'] = $massagem->user->name;
                }
            }
        }

        //CHECAR SE HÁ MAIS DE 4 AGENDAMENTOS PARA O MÊS CORRENTE
        $massagens_usuario_mes = Massagem::where('cancelado', false)
            ->where('usuario', Auth::user()->id)
            ->whereMonth('inicio_data', Carbon::now()->month)
            ->whereYear('inicio_data', Carbon::now()->year)
            ->count();

        if($massagens_usuario_mes >= 4){
            $terca['limite_usuario'] = $quarta['limite_usuario'] = true;
        }

        //CHECAR SE HÁ AGENDAMENTO PARA O DIA - TERÇA
        $massagens_usuario_terca = Massagem::where('cancelado', false)
            ->where('usuario', Auth::user()->id)
            ->whereDate('inicio_data', Carbon::parse($terca['dia'])->format('Y-m-d'))
            ->count();

        if($massagens_usuario_terca >= 1){
            $terca['limite_usuario'] = true;
        }

        //CHECAR SE HÁ AGENDAMENTO PARA O DIA - QUARTA
        $massagens_usuario_quarta = Massagem::where('cancelado', false)
            ->where('usuario', Auth::user()->id)
            ->whereDate('inicio_data', Carbon::parse($quarta['dia'])->format('Y-m-d'))
            ->count();

        if($massagens_usuario_quarta >= 1){
            $quarta['limite_usuario'] = true;
        }

        //CHECA PERFIL PARA EXIBIR LINK DA VIEW C/ TODOS OS AGENDAMENTOS
        if(Auth::user()->tipo == 'admin' || Auth::user()->email == 'recepcao@chebabi.com'){
            $link_todos_agendamentos = true;
        }else{
            $link_todos_agendamentos = false;
        }
        $title = 'Agendamento Massagem | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.agendamento_massagem', compact('title','terca','quarta','link_todos_agendamentos'));
    }

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

    public function contatos(){
        $users = DB::table('users')
                ->select('id', 'name', 'ramal', 'telefone', 'email')
                ->where('ativo', TRUE)
                ->orderBy('name')
                ->get();
        $title = 'Contatos | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.contatos', compact('title', 'users'));
    }

    public function clientes(){
        $clientes = Cliente::with('advogado_civel_1','advogado_civel_2','advogado_civel_3','advogado_trab_1','advogado_trab_2','advogado_trab_3')
            ->where('ativo', true)
            ->get();
        $title = 'Clientes | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.clientes', compact('title', 'clientes'));
    }
    
    public function aniversariantes(){
        $mes = Carbon::today()->month;
        
        $users = DB::table('users')
            ->where('ativo', TRUE)
            ->whereMonth('nascimento', '=', $mes)
            ->orderByRaw(DB::raw("DAY(nascimento) ASC"))
            ->paginate(30);

        $title = 'Aniversariantes | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.aniversariantes', compact('title', 'users', 'mes'));
    }

    public function aniversariantes_filtrados(Request $request){

        $validatedData = Validator::make($request->all(), [
            'mes' => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12',
        ]);

        if (!$validatedData->fails()){

            $mes = $request->mes;
        
            $users = DB::table('users')
                ->where('ativo', TRUE)
                ->whereMonth('nascimento', '=', $mes)
                ->orderByRaw(DB::raw("DAY(nascimento) ASC"))
                ->paginate(30);
        
        $title = 'Aniversariantes | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.aniversariantes_filtrados', compact('title', 'users', 'mes'));

        }else{
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    }
    
    public function procedimentos(){

        if(Auth::user()->tipo == "admin"){
            $procedimentos = DB::table('procedimentos')
            ->orderBy('created_at', 'desc')
            ->get();
        }else{
            $procedimentos = DB::table('procedimentos')
            ->where(function ($query) {
            $query->where('tipo', '=', 'Geral')
            ->orWhere('tipo', '=', Auth::user()->tipo);})
            ->orderBy('created_at', 'desc')
            ->get();
        }

        $title = 'Procedimentos | Intranet Izique Chebabi Advogados Associados';
        
        return view('intranet.procedimentos', compact('title', 'procedimentos'));
    }
    
    public function tarifadores(){

        $tarifadores = DB::table('tarifadores')
            ->orderBy('cliente')
            ->get();

        $title = 'Tarifadores | Intranet Izique Chebabi Advogados Associados';
        
        return view('intranet.tarifadores', compact('title', 'tarifadores'));
    }
    
    public function tutoriais(){

        $tutoriais = DB::table('tutoriais')
            ->orderBy('created_at', 'desc')
            ->get();

        $title = 'Tutoriais | Intranet Izique Chebabi Advogados Associados';
        
        return view('intranet.tutoriais', compact('title', 'tutoriais'));
    }
    
    public function relatorio()
    {
        $title = 'Relatório de viagem | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.relatorio', compact('title'));
    }
    
    public function pdf($nomepasta = null, $nomesubpasta = null, $nomepdf = null){
        //ESSE IF PARA PROTEGER AS DEMAIS PASTAS NÃO FUNCIONA
        /*
        if($nomepasta != 'procedimentos' || $nomepasta != 'tutoriais'){
            return abort(403, 'Não autorizado.');
        }
        */
        if(empty($nomesubpasta)){
            return \Response::make(\Storage::get(
                    'intranet/pdf/'.$nomepasta.'/'.$nomepdf), 200, 
                    ['Content-Type' => 'application/pdf']); 
        }
        
        if($nomesubpasta == Auth::user()->tipo || $nomesubpasta == 'geral'){
            return \Response::make(\Storage::get(
                    'intranet/pdf/'.$nomepasta.'/'.$nomesubpasta.'/'.$nomepdf), 200, 
                    ['Content-Type' => 'application/pdf']); 
        }else if (Auth::user()->tipo == 'admin'){
            return \Response::make(\Storage::get(
                    'intranet/pdf/'.$nomepasta.'/'.$nomesubpasta.'/'.$nomepdf), 200, 
                    ['Content-Type' => 'application/pdf']); 
        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function tutoriais_pdf($nomepasta = null, $nomepdf = null){

        if($nomepasta == 'tutoriais'){
            return \Response::make(\Storage::get(
                    'intranet/pdf/'.$nomepasta.'/'.$nomepdf), 200, 
                    ['Content-Type' => 'application/pdf']); 
        }else{
            return abort(403, 'Não autorizado.');
        }
    }

    public function ranking_uau(){
        //RANKING UAU//
        $ranking = DB::table('uaus')
        ->join('users', 'uaus.para', '=', 'users.id')
        ->select('uaus.*', 'users.name', 'users.ativo')
        ->where('users.ativo', TRUE)
        ->orderBy('users.name', 'Asc');

        if(Carbon::now()->month > 0 && Carbon::now()->month < 7){
            //1º SEMESTRE
            $ranking->whereYear('uaus.created_at', Carbon::now()->year);
        }else{
            //2º SEMESTRE
            $ranking->whereYear('uaus.created_at', Carbon::now()->year)
                ->whereMonth('uaus.created_at', '>', '6');
        }

        $ranking = $ranking->get()->groupBy('name');
        //ORDEM ALFABÉTICA DEPOIS DE ORDENAR PELO COUNT DO GROUP BY
        $ranking_sorted = [];
        $count = 0;
        foreach($ranking as $key => $user){
            $ranking_sorted[$user->count()][]['name'] = $key;
            $count++;
        }
        //SORT BY MAIOR NUMERO UAUS
        krsort($ranking_sorted);
        //DROPA ATÉ DEIXAR SÓ O TOP 10
        while($count > 10){
            end($ranking_sorted);
            $key = key($ranking_sorted);
            //array_key_last REQUER PHP 7.3
            array_pop($ranking_sorted[$key]);
            $count--;
        }
        return($ranking_sorted);
    }

    public function uau(){
        $uaus = Uau::with('de_nome:id,name', 'para_nome:id,name,ativo')
            ->orderBy('created_at', 'Desc')
            ->paginate(10);

        $ranking_sorted = $this->ranking_uau();

        $unread_uaus = DB::table('uaus')->where([
            ['para', Auth::user()->id],
            ['lido', '0'],
        ])->count();
        
        $count_uaus = DB::table('uaus')->count();

        $title = 'Uau | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.uau', compact('title', 'uaus', 'ranking_sorted', 'unread_uaus', 'count_uaus'));
    }
    
    public function meus_uaus(){
        $uaus = Uau::with('de_nome:id,name', 'para_nome:id,name')
            ->where('para', Auth::user()->id)
            ->orderBy('created_at', 'Desc')
            ->paginate(20);
        $title = 'Uau | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.meus_uaus', compact('title', 'uaus'));
    }

    public function uaus_enviados(){
        $uaus = Uau::with('de_nome:id,name', 'para_nome:id,name')
            ->where('de', Auth::user()->id)
            ->orderBy('created_at', 'Desc')
            ->paginate(20);
        $title = 'Uaus Enviados | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.uaus_enviados', compact('title', 'uaus'));
    }

    public function editar_uau($id){
        
        $uau = Uau::with('de_nome:id,name', 'para_nome:id,name')->find($id);
        
        if(!empty($uau) && Auth::user()->id == $uau->de_nome->id){

            $title = 'Editar Uau | Intranet Izique Chebabi Advogados Associados';
            return view('intranet.editar_uau', compact('title', 'uau'));

        }else{
            return null;
        }

    }

    public function atualiza_uau(Request $request, $id){
        
        $uau = Uau::with('de_nome:id,name', 'para_nome:id,name')->find($id);

        if(!empty($uau) && Auth::user()->id == $uau->de_nome->id){

            $validator = Validator::make($request->all(),$uau->rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }else{
                $uau->motivo = $request->motivo;
                $uau->updated_at = Carbon::now();
                $uau->save();
                $request->session()->flash('alert-success', 'Uau editado com sucesso!');
                return redirect()->action('Intranet\IntranetController@index');
            }
        }else{
            return null;
        }

    }
    
    public function novo_uau(){
        
        $users = DB::table('users')->select('name', 'id')
            ->where([
                        ['ativo', '=',TRUE], 
                        ['id', '!=', Auth::id()]
                    ])
            ->orderBy('name')
            ->get();
        
        $title = 'Enviar Uau | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.novo_uau', compact('title', 'users'));
    }
    
    public function enviar_uau(Request $request){
        
        $uau = new Uau;
        
        $validator = Validator::make($request->all(),$uau->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            $uau->de = Auth::user()->id;
            $uau->para = $request->para;
            $uau->motivo = $request->motivo;
            $uau->lido = 0;

            $destinatario = DB::table('users')
                ->select('email', 'name')
                ->where('id', $request->para)
                ->first();
            
            $uau->save();
            //ENVIAR NOTIFICAÇÃO POR E-MAIL
            Mail::send('emails.uau', ['content' => $destinatario->name], function ($message) use ($destinatario)
            {
                $message->from('uau@chebabi.adv.br', 'Uau - Izique Chebabi Advogados');
                $message->to($destinatario->email, $name = null);
                $message->subject('Você recebeu um Uau!');
            });
            
            DB::table('users')->whereId($uau->para)->increment('uaus');
            $request->session()->flash('alert-success', 'Uau enviado com sucesso!');
            return redirect()->action('Intranet\IntranetController@index');
        }
    }

    public function uau_lido(Request $request){

        $uau_id = key($request->all());
        
        DB::table('uaus')
            ->where('id', $uau_id)
            ->update(['lido' => 1]);
    }

    public function clientes_relatorio_viagem(Request $request){

        $cliente = key($request->all());

        $clientes = array_filter(explode('_', $cliente));
        
        $response = DB::table('valores_km_clientes')->where(function ($q) use ($clientes) {
        foreach ($clientes as $value) {
            if(!empty($value) && strlen($value)>2 
                && $value !== 'LTDA' && $value !== 'INDÚSTRIA' && $value !== 'INDUSTRIA' 
                && $value !== 'COMERCIO' && $value !== 'COMÉRCIO' && $value !== 'S/A'
                && $value !== 'BRASIL'){
                $q->orWhere('cliente', 'like', '%'.$value.'%');
            }
        }
        })->orWhere('cliente', null)->orderBy('cliente', 'asc')->get();
        
        if(!empty($response)){
            return Response::json($response);
        }else{
            return null;
        }

    }

    public function tutorial_relatorio_viagem(){
        return view('intranet.tutorial_relatorio_viagem');
    }
        
}