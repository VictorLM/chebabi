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

class SiteController extends Controller
{
    public function index(){
        $title = 'Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.index', compact('title'));
    }
    
    public function contato(){
        $title = 'Contato | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.contato', compact('title'));
    }
    
    public function enviar_contato(Request $request){
        
        $validator = Validator::make($request->all(), [
            'remetenteNome' => 'required|string|max:50',
            'telefone' => 'nullable|string|max:17',
            'remetenteEmail' => 'required|email|max:100',
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
                $message->from($request->input('remetenteEmail'), $name = null);
                $message->to('atendimento@chebabi.com', $name = null);
                $message->subject('Contato enviado pelo site');
            });

            if (Mail::failures()) {
                $request->session()->flash('alert-success', 'Erro ao enviar! tente novamente mais tarde.');
                return redirect()->action('Site\SiteController@contato');
            }else{
                DB::table('contatos')->insert([
                    [
                        'nome' => $request->input('remetenteNome'), 
                        'telefone' => $request->input('telefone'), 
                        'email' => $request->input('remetenteEmail'), 
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
            'remetenteNome' => 'required|string|max:50',
            'telefone' => 'nullable|string|max:17',
            'remetenteEmail' => 'required|email|max:100',
            'rg' => 'required|string|max:13',
            'cpf' => 'required|string|max:14',
            'endereco' => 'required|string|max:100',
            'data' => 'required|date',
            'area' => [
                'required',
                Rule::in(
                    ['Advogado Cível', 'Advogado Trabalhista', 'Apoio Jurídico', 'Estágio Cível', 
                     'Estágio Trabalhista', 'Financeiro', 'Área Administrativa']),
            ],
            'curriculo' => 'required|mimes:pdf|max:5240',
            'mensagem' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $destinatario2 = "";
            switch($request->input('area')){
                case "Advogado Cível": $destinatario = "paula@chebabi.com"; break;
                case "Advogado Trabalhista": $destinatario = "maria.carolina@chebabi.com"; 
                                            $destinatario2 = "daniele@chebabi.com"; break;
                case "Apoio Jurídico": $destinatario = "rafaela@chebabi.com"; break;
                case "Estágio Cível": $destinatario = "maira@chebabi.com"; break;
                case "Estágio Trabalhista": $destinatario = "maria.carolina@chebabi.com"; break;
                case "Financeiro": $destinatario = "dp@chebabi.com"; break;
                case "Área Administrativa": $destinatario = "dp@chebabi.com"; break;
                //case "Área Administrativa": $destinatario = "victor@chebabi.com"; break;
                default: $destinatario = "dp@chebabi.com"; break;
            }
        
            $identificador = date('dmYHis');
            if ($request->hasFile('curriculo')){
                $request->file('curriculo')->storeAs('curriculos', 'curriculo_'.$identificador.'.pdf');
            }
            $content = $request->all();
            Mail::send('emails.curriculo', ['content' => $content], 
                        function ($message) use ($destinatario, $destinatario2, $identificador, $request)
            {
                $message->from($request->input('remetenteEmail'), $name = null);
                $message->to($destinatario, $name = null);
                $message->subject('Currículo enviado pelo formulário do site');
                if(!empty($destinatario2)){
                    $message->cc($destinatario2, $name = null);
                }
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
                        'nome' => $request->input('remetenteNome'), 
                        'email' => $request->input('remetenteEmail'), 
                        'telefone' => $request->input('telefone'),
                        'rg' => $request->input('rg'),
                        'cpf' => $request->input('cpf'),
                        'endereco' => $request->input('endereco'),
                        'nascimento' => $request->input('data'),
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
        $title = 'Trabalhe Conosco | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.trabalheconosco', compact('title'));
    }
    
    public function escritorios(){
        $title = 'Escritórios | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.escritorios', compact('title'));
    }
    
    public function areas(){
        $title = 'Áreas | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.areas', compact('title'));
    }
    
    public function noticias(){
        $feed = 'https://www.aasp.org.br/feed/?post_type=noticias';
        $noticias = simplexml_load_file($feed);
        $title = 'Notícias | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.noticias', compact('title', 'noticias'));
    }
    
    public function advogados(){
        $title = 'Advogados | Izique Chebabi Advogados Associados | Advogados Campinas São Paulo Advocacia';
        return view('site.advogados', compact('title'));
    }
}
