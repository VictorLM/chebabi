<?php

namespace Intranet\Http\Controllers\Site;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Intranet\Advogados;
use Carbon\Carbon;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Intranet\Blog_Noticias;
use Jenssegers\Agent\Agent;

class SiteController extends Controller
{
    public function index(){
        $agent = new Agent();
        $artigos = DB::table('blog_artigos')->orderBy('created_at', 'DESC')->limit(7)->get();
        $noticias = Blog_Noticias::orderBy('publicacao', 'DESC')->limit(5)->get();
        $historias = DB::table('blog_historias')->orderBy('created_at', 'DESC')->limit(2)->get();
        $title = 'Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.index', compact('title', 'noticias', 'artigos', 'historias', 'agent'));
    }
    
    public function contato(){
        $agent = new Agent();
        $title = 'Contato | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.contato', compact('title', 'agent'));
    }
    
    public function enviar_contato(Request $request){
        
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:50',
            'telefone' => 'nullable|string|max:17',
            'email' => 'required|email|max:100',
            'mensagem' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $content = $request->all();
            Mail::send('emails.contato', ['content' => $content], 
                        function ($message) use ($request)
            {
                $message->from($request->input('email'), $name = null);
                $message->to('atendimento@chebabi.com', $name = null);
                $message->subject('Contato enviado pelo site');
            });

            if (Mail::failures()) {
                $request->session()->flash('alert-error', 'Erro ao enviar! tente novamente mais tarde.');
                return redirect()->action('Site\SiteController@contato');
            }else{
                DB::table('contatos')->insert([
                    [
                        'nome' => $request->input('nome'), 
                        'telefone' => $request->input('telefone'), 
                        'email' => $request->input('email'), 
                        'ip' => 'IP: ' . \Request::ip() .' - USER-AGENT: ' . $request->header('User-Agent'),
                        'mensagem' => strip_tags($request->input('mensagem')),
                        'created_at' => Carbon::now(),
                    ]
                ]);                
                $request->session()->flash('alert-success', 'Mensagem enviada com sucesso!');
                return redirect()->action('Site\SiteController@contato');
            }

        }
    }
    
    public function enviar_curriculo(Request $request){
        
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:50',
            'telefone' => 'required|string|max:17',
            'email' => 'required|email|max:100',
            'rg' => 'required|string|max:13',
            'cpf' => 'required|string|max:14',
            'endereco' => 'required|string|max:100',
            'nascimento' => 'required|date',
            'area' => [
                'required',
                Rule::in(
                    ['Advogado(a) Cível', 'Advogado(a) Trabalhista', 'Apoio Jurídico', 'Estágio Cível', 
                     'Estágio Trabalhista', 'Financeiro', 'Área Administrativa']),
            ],
            'curriculo' => 'required|mimes:pdf|max:2400',
            'mensagem' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $titulo = "";
            switch($request->input('area')){
                case "Advogado(a) Cível": $destinatario = "curriculos@chebabi.com"; 
                                        $titulo = "[Cível] Currículo enviado pelo formulário do site"; break;
                case "Advogado(a) Trabalhista": $destinatario = "curriculos@chebabi.com"; 
                                        $titulo = "[Trabalhista] Currículo enviado pelo formulário do site"; break;
                case "Apoio Jurídico": $destinatario = "curriculos@chebabi.com"; 
                                        $titulo = "[Adm Jur] Currículo enviado pelo formulário do site"; break;
                case "Estágio Cível": $destinatario = "curriculos@chebabi.com"; 
                                        $titulo = "[Estágio Cível] Currículo enviado pelo formulário do site"; break;
                case "Estágio Trabalhista": $destinatario = "curriculos@chebabi.com"; 
                                        $titulo = "[Estágio Trabalhista] Currículo enviado pelo formulário do site"; break;
                case "Financeiro": $destinatario = "dp@chebabi.com"; 
                                        $titulo = "[Financeiro] Currículo enviado pelo formulário do site"; break;
                case "Área Administrativa": $destinatario = "dp@chebabi.com"; 
                                        $titulo = "[Adm] Currículo enviado pelo formulário do site"; break;
                default: $destinatario = "dp@chebabi.com"; 
                                        $titulo = "Currículo enviado pelo formulário do site"; break;
            }
        
            $identificador = date('dmYHis');
            if ($request->hasFile('curriculo')){
                $request->file('curriculo')->storeAs('curriculos', 'curriculo_'.$identificador.'.pdf');
            }
            $content = $request->all();
            Mail::send('emails.curriculo', ['content' => $content], 
                        function ($message) use ($destinatario, $titulo, $identificador, $request)
            {
                $message->from($request->input('email'), $name = null);
                $message->to($destinatario, $name = null);
                $message->subject($titulo);
                if ($request->hasFile('curriculo')){
                    $message->attach("../storage/app/curriculos/curriculo_".$identificador.".pdf");
                }
            });

            if (Mail::failures()) {
                $request->session()->flash('alert-success', 'Erro ao enviar! tente novamente mais tarde.');
                return redirect()->action('Site\SiteController@trabalheconosco');
            }else{
                DB::table('curriculos')->insert([
                    [
                        'nome' => $request->input('nome'), 
                        'email' => $request->input('email'), 
                        'telefone' => $request->input('telefone'),
                        'rg' => $request->input('rg'),
                        'cpf' => $request->input('cpf'),
                        'endereco' => $request->input('endereco'),
                        'nascimento' => $request->input('nascimento'),
                        'area' => $request->input('area'),
                        'ip' => 'IP: ' . \Request::ip() .' - USER-AGENT: ' . $request->header('User-Agent'),
                        'mensagem' => strip_tags($request->input('mensagem')),
                        'created_at' => Carbon::now(),
                    ]
                ]);
                $request->session()->flash('alert-success', 'Seu currículo foi enviado com sucesso. Boa sorte!');
                return redirect()->action('Site\SiteController@trabalheconosco');
            }

        }
    }
    
    public function trabalheconosco(){
        $agent = new Agent();
        $title = 'Trabalhe Conosco | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.trabalheconosco', compact('title', 'agent'));
    }
    
    public function escritorios(){
        $agent = new Agent();
        $title = 'Escritórios | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.escritorios', compact('title', 'agent'));
    }
    
    public function areas(){
        $agent = new Agent();
        $title = 'Áreas | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.areas', compact('title', 'agent'));
    }
    
    public function equipe(){
        $agent = new Agent();
        $equipe = Advogados::with('nome_usuario:id,name,ramal,email')->orderBy('created_at', 'ASC')->get();
        $title = 'Advogados | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.equipe', compact('title', 'equipe', 'agent'));
    }
}
