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
use Validator;
use Intranet\Eventos;

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

        if(Carbon::parse(Auth::user()->nascimento)->format('d/m') 
            == Carbon::parse(Carbon::today())->format('d/m')
            && Carbon::parse($last_login->last_login)->format('Y-m-d') 
            != Carbon::parse(Carbon::today())->format('Y-m-d')){
            $aniversario = TRUE;
        }else{
            $aniversario = FALSE;
        }
        
        if(Auth::user()->tipo == 'admin'){
            $admin = TRUE;
        }else{
            $admin = FALSE;
        }
        
        DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['last_login' => Carbon::now()]);
        
        $title = 'Intranet | Izique Chebabi Advogados Associados';
        return view('intranet.index', compact('title', 'unread_uaus', 'aniversario', 'admin'));
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
                'sugestao' => strip_tags($request->sugestoes),
                'infos_sessao' => 'IP: ' . \Request::ip() .' - USER-AGENT: ' . $request->header('User-Agent'),
                'created_at' => Carbon::now(),
            ]);
            
            $content = $request->all();
            
            Mail::send('emails.sugestao', ['content' => $content], function ($message) use ($content)
            {
                $message->from('postmaster@chebabi.adv.br', 'Sugestão - Izique Chebabi Advogados');
                $message->to('victor@chebabi.com', $name = null);
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
        
        return \Response::json($eventos);
    }
    
    public function novo_evento(){
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->where('ativo', TRUE)
            ->orderBy('name')
            ->get();
        $title = 'Novo Evento | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.novo_evento', compact('title', 'users'));
    }
    
    public function showEvento(Request $request, $id){
        $evento = Eventos::find($id);
        $title = 'Evento da Agenda | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.evento', compact('title', 'evento'));
    }
    
    public function contatos()
    {
        $users = DB::table('users')
                ->select('id', 'name', 'ramal', 'telefone', 'email')
                ->where('ativo', TRUE)
                ->orderBy('name')
                ->paginate(20);
        $title = 'Contatos | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.contatos', compact('title', 'users'));
    }
    
    public function aniversariantes()
    {
        $mes = Carbon::today()->month;
        
        $users = DB::table('users')
                ->where('ativo', TRUE)
                ->whereMonth('nascimento', '=', $mes)
                ->orderBy('name')
                ->paginate(20);
        
        switch($mes){
            case 1: $mes = "Janeiro"; break;
            case 2: $mes = "Fevereiro"; break;
            case 3: $mes = "Março"; break;
            case 4: $mes = "Abril"; break;
            case 5: $mes = "Maio"; break;
            case 6: $mes = "Junho"; break;
            case 7: $mes = "Julho"; break;
            case 8: $mes = "Agosto"; break;
            case 9: $mes = "Setembro"; break;
            case 10: $mes = "Outubro"; break;
            case 11: $mes = "Novembro"; break;
            case 12: $mes = "Dezembro"; break;
            default: $mes = "Mês"; break;
        }
        
        $title = 'Aniversariantes | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.aniversariantes', compact('title', 'users', 'mes'));
    }
    
    public function procedimentos(){

        if(Auth::user()->tipo == "admin"){
            $procedimentos = DB::table('procedimentos')
            ->orderBy('name')
            ->paginate(20);
        }else{
            $procedimentos = DB::table('procedimentos')
            ->where(function ($query) {
            $query->where('tipo', '=', 'Geral')
            ->orWhere('tipo', '=', Auth::user()->tipo);})
            ->orderBy('name')
            ->paginate(20);
        }

        $title = 'Procedimentos | Intranet Izique Chebabi Advogados Associados';
        
        return view('intranet.procedimentos', compact('title', 'procedimentos'));
    }
    
    public function tarifadores(){

        $tarifadores = DB::table('tarifadores')
            ->orderBy('cliente')
            ->paginate(20);

        $title = 'Tarifadores | Intranet Izique Chebabi Advogados Associados';
        
        return view('intranet.tarifadores', compact('title', 'tarifadores'));
    }
    
    public function tutoriais(){

        $tutoriais = DB::table('tutoriais')
            ->orderBy('name')
            ->paginate(20);

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

    public function uau(){
        $uaus = Uau::with('de_nome:id,name', 'para_nome:id,name,ativo')
            ->orderBy('created_at', 'Desc')
            ->limit(200)
            ->paginate(20);

        $ranking = DB::table('users')
            ->select('id', 'name', 'uaus')
            ->where([
                ['ativo', '=',TRUE], 
                ['uaus', '>', 0]
             ])
            ->orderBy('uaus', 'Desc')
            ->limit(10)
            ->get();
        
        $unread_uaus = DB::table('uaus')->where([
            ['para', Auth::user()->id],
            ['lido', '0'],
        ])->count();
        
        $title = 'Uau | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.uau', compact('title', 'uaus', 'ranking', 'unread_uaus'));
    }
    
    public function meus_uaus(){
        $uaus = Uau::with('de_nome:id,name', 'para_nome:id,name')
            ->where('para', Auth::user()->id)
            ->orderBy('created_at', 'Desc')
            ->limit(100)
            ->paginate(20);
        $title = 'Uau | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.meus_uaus', compact('title', 'uaus'));
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
        
}