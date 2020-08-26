<?php

namespace Intranet\Http\Controllers\Correios;

use Illuminate\Http\Request;
use Intranet\Http\Controllers\Controller;
use Intranet\Correio;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CorreiosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $title = 'Envio Correios | Intranet Izique Chebabi Advogados Associados';
        return view('intranet.correios.index', compact('title'));
    }


    public function store(Request $request){

        $correio = new Correio;
        $validator = Validator::make($request->all(), $correio->rules, $correio->customMessages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            
            $identificador = Auth::user()->id . "_" . date('dmYHis');
            
            $correio->fill($request->all());
            $correio->user = Auth::user()->id;
            $correio->data = Carbon::now();
            $correio->anexo = $identificador;
            
            try {
                // throw new \Symfony\Component\HttpKernel\Exception\HttpException(500); - TODO
                
                $request->file('anexo')->storeAs('intranet/pdf/correios/anexos/', 'Correio_' . $correio->anexo . '.pdf');

                if($correio->save() && $correio->toPdf() && $correio->toEmail()){
                    
                    $request->session()->flash('alert-success', 'Solicitação de correio enviada com sucesso!');
                    return redirect()->action('Intranet\IntranetController@index');
                }
                
                return redirect()->back()->withErrors('Erro interno. Tente novamente mais tarde.')->withInput();

            } catch(Exception $e) {
                // REPORTAR $e - TODO
                return redirect()->back()->withErrors('Erro interno. Tente novamente mais tarde.')->withInput();
            }

        }
    }

}
