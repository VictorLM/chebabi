<?php

namespace Intranet\Http\Controllers\Relatorio;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Intranet\User;
use Intranet\Relatorio;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RelatorioController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('Intranet\Http\Middleware\Admin::class', ['only' => ['relatorios', 'relatorios_user']]);
    }

    public function create(Request $request){

        $identificador = date('dmYHis');
        
        $relatorio = new Relatorio;

        $validator = Validator::make($request->all(),$relatorio->rules);
        
        if ($request->hasFile('comprovantes')){
            $request->file('comprovantes')->storeAs('intranet\pdf\comprovantes', 'comprovante_'.$identificador.'.pdf');
        }

        $request->request->add(['usuario' => Auth::user()->id]);
        $request->request->add(['created_at' => Carbon::now()]);
        $request->request->add(['identificador' => $identificador]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            
            $this->relatorio_pdf($request, $identificador);
            $this->relatorio_email($identificador, $request);
            $this->relatorio_email_copia($identificador, $request);
            
            Relatorio::create($request->except(['valorkm', 'totalgastos', 'aserdevolvido', 'enviar', 'responsavel']));
            $request->session()->flash('alert-success', 'Relatório enviado com sucesso!');
            return redirect()->action('Intranet\IntranetController@index');
        }
    }
    
    private function relatorio_pdf($request, $identificador){
        //mPDF
        require_once __DIR__ . '/../../../../vendor/autoload.php';
        /* GERANDO PDF COM MPDF */
        //GERANDO VIA DO FINANCEIRO
        $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8', 
                'format' => 'A4', 
                'orientation' => 'P',
                'setAutoTopMargin' => 'stretch'
        ]);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->list_indent_first_level = 0;

        $header = '
            <div style="float:right; width:250px;">
            <img src="../public/assets/imagens/logo3.png">
            </div>';
        
        if($request->input('tipo_viagem') == "Com kilometragem"){
            //HTML TEMPLATE
            require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem.php';
            
            $stylesheet = file_get_contents('../public/assets/css/pdf.css');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetHTMLHeader($header,'',TRUE);
            $mpdf->WriteHTML($html1, 2);
            $mpdf->Output("../storage/app/intranet/pdf/relatorios/relatorio_viagem_".$identificador.".pdf","F");
            
            //GERANDO VIA DO CLIENTE

            if(!empty($request->input('pasta2')) && !empty($request->input('cliente2')) &&
                empty($request->input('pasta3')) && empty($request->input('cliente3'))){
                //COM DOIS CLIENTES;
                $i = 1;

                require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_cliente.php';

                while($i<3){

                    $mpdf = new \Mpdf\Mpdf([
                        'mode' => 'utf-8', 
                        'format' => 'A4-L', 
                        'setAutoTopMargin' => 'stretch'
                    ]);
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->list_indent_first_level = 0;

                    $stylesheet = file_get_contents('../public/assets/css/pdf.css');
                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->SetHTMLHeader($header,'',TRUE);
                    $mpdf->SetHTMLFooter($footer,'');
                    $mpdf->WriteHTML(${'html'.$i}, 2);
                    $mpdf->Output("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente".$i."_".$identificador.".pdf","F");

                    $i++;
                }

            }else if (!empty($request->input('pasta2')) && !empty($request->input('cliente2')) &&
                      !empty($request->input('pasta3')) && !empty($request->input('cliente3'))){
                //COM TRÊS CLIENTES;
                $i = 1;

                require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_cliente.php';

                while($i<4){

                    $mpdf = new \Mpdf\Mpdf([
                        'mode' => 'utf-8', 
                        'format' => 'A4-L', 
                        'setAutoTopMargin' => 'stretch'
                    ]);
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->list_indent_first_level = 0;

                    $stylesheet = file_get_contents('../public/assets/css/pdf.css');
                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->SetHTMLHeader($header,'',TRUE);
                    $mpdf->SetHTMLFooter($footer,'');
                    $mpdf->WriteHTML(${'html'.$i}, 2);
                    $mpdf->Output("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente".$i."_".$identificador.".pdf","F");

                    $i++;
                }

            }else{
                //COM UM CLIENTE;
                require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_cliente.php';

                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8', 
                    'format' => 'A4-L', 
                    'setAutoTopMargin' => 'stretch'
                ]);
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->list_indent_first_level = 0;

                $stylesheet = file_get_contents('../public/assets/css/pdf.css');
                $mpdf->WriteHTML($stylesheet, 1);
                $mpdf->SetHTMLHeader($header,'',TRUE);
                $mpdf->SetHTMLFooter($footer,'');
                $mpdf->WriteHTML($html1, 2);
                $mpdf->Output("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente_".$identificador.".pdf","F");
            }
            
        }else{
            //HTML TEMPLATE
            require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_skm.php';
            
            $stylesheet = file_get_contents('../public/assets/css/pdf.css');
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetHTMLHeader($header,'',TRUE);
            $mpdf->WriteHTML($html1, 2);
            $mpdf->Output("../storage/app/intranet/pdf/relatorios/relatorio_viagem_".$identificador.".pdf","F");
            
            //GERANDO VIA DO CLIENTE

            if(!empty($request->input('pasta2')) && !empty($request->input('cliente2')) &&
                empty($request->input('pasta3')) && empty($request->input('cliente3'))){
                //COM DOIS CLIENTES;
                $i = 1;

                require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_cliente_skm.php';

                while($i<3){

                    $mpdf = new \Mpdf\Mpdf([
                        'mode' => 'utf-8', 
                        'format' => 'A4-L', 
                        'setAutoTopMargin' => 'stretch'
                    ]);
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->list_indent_first_level = 0;

                    $stylesheet = file_get_contents('../public/assets/css/pdf.css');
                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->SetHTMLHeader($header,'',TRUE);
                    $mpdf->SetHTMLFooter($footer,'');
                    $mpdf->WriteHTML(${'html'.$i}, 2);
                    $mpdf->Output("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente".$i."_".$identificador.".pdf","F");

                    $i++;
                }

            }else if (!empty($request->input('pasta2')) && !empty($request->input('cliente2')) &&
                      !empty($request->input('pasta3')) && !empty($request->input('cliente3'))){
                //COM TRÊS CLIENTES;
                $i = 1;

                require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_cliente_skm.php';

                while($i<4){

                    $mpdf = new \Mpdf\Mpdf([
                        'mode' => 'utf-8', 
                        'format' => 'A4-L', 
                        'setAutoTopMargin' => 'stretch'
                    ]);
                    $mpdf->SetDisplayMode('fullpage');
                    $mpdf->list_indent_first_level = 0;

                    $stylesheet = file_get_contents('../public/assets/css/pdf.css');
                    $mpdf->WriteHTML($stylesheet, 1);
                    $mpdf->SetHTMLHeader($header,'',TRUE);
                    $mpdf->SetHTMLFooter($footer,'');
                    $mpdf->WriteHTML(${'html'.$i}, 2);
                    $mpdf->Output("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente".$i."_".$identificador.".pdf","F");

                    $i++;
                }

            }else{
                //COM UM CLIENTE;
                require_once __DIR__ . '/../../../../app/Http/Controllers/Relatorio/template_relatorio_viagem_cliente_skm.php';

                $mpdf = new \Mpdf\Mpdf([
                    'mode' => 'utf-8', 
                    'format' => 'A4-L', 
                    'setAutoTopMargin' => 'stretch'
                ]);
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->list_indent_first_level = 0;

                $stylesheet = file_get_contents('../public/assets/css/pdf.css');
                $mpdf->WriteHTML($stylesheet, 1);
                $mpdf->SetHTMLHeader($header,'',TRUE);
                $mpdf->SetHTMLFooter($footer,'');
                $mpdf->WriteHTML($html1, 2);
                $mpdf->Output("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente_".$identificador.".pdf","F");
            }
        
        }

    }
    
    //WHILE PARA ANEXAR QUANDO HOUVER MAIS DE UM CLIENTE
    private function relatorio_email($identificador, $request){
        $request->request->add(['responsavel' => Auth::user()->name]);
        $content = $request->all();
        Mail::send('emails.relatorio', ['content' => $content], 
                    function ($message) use ($identificador, $request)
        {
            $message->from('relatorios@chebabi.adv.br', 'Relatórios de viagem');
            $message->to('relatorios@chebabi.com', $name = null);
            $message->subject('Relatório de viagem - Protocolo: '.$identificador);
            $message->attach("../storage/app/intranet/pdf/relatorios/relatorio_viagem_".$identificador.".pdf");

            if(file_exists("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente_".$identificador.".pdf")){
                $message->attach("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente_".$identificador.".pdf");
            }
            if(file_exists("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente1_".$identificador.".pdf")){
                $message->attach("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente1_".$identificador.".pdf");
            }
            if(file_exists("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente2_".$identificador.".pdf")){
                $message->attach("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente2_".$identificador.".pdf");
            }
            if(file_exists("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente3_".$identificador.".pdf")){
                $message->attach("../storage/app/intranet/pdf/relatorios/cliente/relatorio_viagem_cliente3_".$identificador.".pdf");
            }

            if ($request->hasFile('anexo')){
                $message->attach("../storage/app/intranet/pdf/comprovantes/comprovante_".$identificador.".pdf");
            }
        });
        //IMPLEMENTAR CHECAGEM DE ENVIO
        if (Mail::failures()) {
            return (false);
        }else{
            return (true);
        }
    }
    
    private function relatorio_email_copia($identificador, $request){
        $request->request->add(['responsavel' => Auth::user()->name]);
        $content = $request->all();
        Mail::send('emails.relatorio', ['content' => $content], function ($message) use ($identificador)
        {
            $message->from('relatorios@chebabi.adv.br', 'Relatórios de viagem');
            $message->to(Auth::user()->email, $name = null);
            $message->subject('Relatório de viagem - Protocolo: '.$identificador);
            $message->attach("../storage/app/intranet/pdf/relatorios/relatorio_viagem_".$identificador.".pdf");
        });
    }

    public function relatorios(){

        $users = User::select('id','name')
                ->where('ativo', TRUE)
                ->orderBy('name')
                ->get();
        
        $relatorios = Relatorio::with('nome_usuario:id,name')
                ->limit(20)
                ->orderBy('created_at', 'DESC')
                ->get();
        
        $title = 'Relatórios de viagem | Intranet Izique Chebabi Advogados Associados';
        return view('relatorio.relatorios', compact('title', 'users', 'relatorios'));
    }
    
    public function relatorios_user(Request $request){

        $users = User::select('id','name')
                ->where('ativo', TRUE)
                ->orderBy('name')
                ->get();

        $relatorios_user = Relatorio::with('nome_usuario:id,name')
            ->where('usuario', $request->user)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        $title = 'Relatórios de viagem | Intranet Izique Chebabi Advogados Associados';
        return view('relatorio.relatorios_user', compact('title', 'users','relatorios_user'));
    }
}
