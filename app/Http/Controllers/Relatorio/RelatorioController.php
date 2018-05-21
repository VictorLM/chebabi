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

        $relatorio = new Relatorio;

        $validator = Validator::make($request->all(),$relatorio->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $identificador = date('dmYHis');
            
            if ($request->hasFile('comprovantes')){
                $request->file('comprovantes')->storeAs('intranet\pdf\comprovantes', 'comprovante_'.$identificador.'.pdf');
                $comprovantes = TRUE;
            }else{
                $comprovantes = FALSE;
            }

            $clientes = [];
            $partes_contrarias = [];
            $pastas = [];
            $processos = [];
            $enderecos = [];
            $motivos_viagem = [];
            $despesas = [];
            $i=1;
            $index=0;

            if(!empty($request->carro)){
                $veiculo = mb_strtoupper($request->carro, 'UTF-8');
            }else{
                $veiculo = null;
            }

            while($i<6){
                $clientes[$index]['CLIENTE'] =      mb_strtoupper($request->input('cliente'.$i), 'UTF-8');
                $clientes[$index]['CLIENTE_ID'] =   $request->input('cliente-id'.$i);
                $clientes[$index]['CONTRARIO'] =    mb_strtoupper($request->input('contrario'.$i), 'UTF-8');
                $clientes[$index]['PASTA'] =        mb_strtoupper($request->input('pasta'.$i), 'UTF-8');
                $clientes[$index]['PROCESSO'] =     mb_strtoupper($request->input('proc'.$i), 'UTF-8');
                $clientes[$index]['DESCRICAO'] =    mb_strtoupper($request->input('motivoviagem'.$i), 'UTF-8');
                $enderecos[] =                      mb_strtoupper($request->input('end'.$i), 'UTF-8');
                $despesas[$index]['DESCRIÇÃO'] =    mb_strtoupper($request->input('descricaodespesa'.$i), 'UTF-8');
                $despesas[$index]['PASTA'] =        mb_strtoupper($request->input('despesapasta'.$i), 'UTF-8');
                $despesas[$index]['CLIENTE'] =      mb_strtoupper($request->input('clientedespesa'.$i), 'UTF-8');
                $despesas[$index]['VALOR'] =        $request->input('despesasgerais'.$i);
                $i++;
                $index++;
            }

            $clientes = array_filter(array_map('array_filter', array_filter($clientes)));

            for($i=0; $i<count($clientes); $i++){

                if(isset($clientes[$i]['CLIENTE_ID'])){
                    $clientes[$i]['VALOR_KM'] = DB::table('valores_km_clientes')
                        ->where('id', $clientes[$i]['CLIENTE_ID'])
                        ->value('valor_km') ?? 0.8;
                }else{
                    $clientes[$i]['VALOR_KM'] = 0.8;
                    $clientes[$i]['CLIENTE_ID'] = null;
                }

            }
            
            $relatorio->usuario =       mb_strtoupper(Auth::user()->id, 'UTF-8');
            $relatorio->created_at =    Carbon::now();
            $relatorio->identificador = $identificador;
            $relatorio->kilometragem =  $request->kilometragem;
            $relatorio->veiculo =       $veiculo;
            $relatorio->reembolsavel =  $request->reembolsavel;
            $relatorio->pedagio =       $request->pedagio;
            $relatorio->clientes =      serialize($clientes);
            $relatorio->enderecos =     serialize(array_filter(array_map('strtoupper', $enderecos)));
            $relatorio->despesas =      serialize(array_filter(array_map('array_filter', array_filter($despesas))));
            $relatorio->data = $request->data;
            $relatorio->totalkm = $request->totalkm;
            $relatorio->caucao = $request->caucao;
            $relatorio->observacoes = mb_strtoupper($request->observacoes, 'UTF-8');
            $relatorio->comprovantes = $comprovantes;            

            $relatorio->save();

            $this->relatorio_pdf($relatorio);
            $this->relatorio_email($relatorio);
            $this->relatorio_email_copia($relatorio);

            $request->session()->flash('alert-success', 'Relatório enviado com sucesso!');
            return redirect()->action('Intranet\IntranetController@index');
            
        }
    }
    
    private function relatorio_pdf($relatorio){

        \PDF::loadView('pdf.relatorio_viagem', compact('relatorio'))
            ->save('../storage/app/intranet/pdf/relatorios/relatorio_'.$relatorio->identificador.'.pdf');

        if($relatorio->reembolsavel){
            for($i=0; $i<count(unserialize($relatorio->clientes)); $i++){
                \PDF::loadView('pdf.relatorio_viagem_cliente', compact('relatorio', 'i'))
                    ->setPaper('a4', 'landscape')
                    ->save('../storage/app/intranet/pdf/relatorios/cliente/relatorio_cliente_'.($i+1).'_'.$relatorio->identificador.'.pdf');
            }
            
        }
    }

    private function relatorio_email($relatorio){

        Mail::send('emails.relatorio', ['content' => $relatorio], 
                    function ($message) use ($relatorio)
        {
            $message->from('relatorios@chebabi.adv.br', 'Relatórios de viagem');
            $message->to('relatorios@chebabi.com', $name = null);
            //$message->to('victor@chebabi.com', $name = null);
            $message->subject('Relatório de viagem - '.$relatorio->identificador);
            $message->attach("../storage/app/intranet/pdf/relatorios/relatorio_".$relatorio->identificador.".pdf");

            if($relatorio->reembolsavel){
                for($i=1; $i<5; $i++){
                    if(file_exists("../storage/app/intranet/pdf/relatorios/cliente/relatorio_cliente_".$i."_".$relatorio->identificador.".pdf")){
                        $message->attach("../storage/app/intranet/pdf/relatorios/cliente/relatorio_cliente_".$i."_".$relatorio->identificador.".pdf");
                    }
                }
            }

            if ($relatorio->comprovantes && 
                file_exists("../storage/app/intranet/pdf/comprovantes/comprovante_".$relatorio->identificador.".pdf")){
                    
                $message->attach("../storage/app/intranet/pdf/comprovantes/comprovante_".$relatorio->identificador.".pdf");
            }
        });
    }
    
    private function relatorio_email_copia($relatorio){

        Mail::send('emails.relatorio', ['content' => $relatorio], 
                    function ($message) use ($relatorio)
        {
            $message->from('relatorios@chebabi.adv.br', 'Relatórios de viagem');
            $message->to(Auth::user()->email, $name = Auth::user()->name);
            $message->subject('Relatório de viagem - '.$relatorio->identificador);
            $message->attach("../storage/app/intranet/pdf/relatorios/relatorio_".$relatorio->identificador.".pdf");
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
