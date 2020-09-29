<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Correio extends Model{

    public $rules = [
        'reembolsavel' => 'required|boolean',
        'motivo' => 'nullable|required_if:reembolsavel,0|string|max:191',
        'tipo' => 'required|in:Carta_AR,Sedex,Sedex_10',
        'pasta' => 'required|string|max:7',
        'cliente' => 'required|string|max:191',
        'destinatario' => 'required|string|max:191',
        'ac' => 'required|string|max:191',
        'cep' => 'required|string|max:10',
        'rua' => 'required|string|max:191',
        'numero' => 'required|string|max:9',
        'complemento' => 'nullable|string|max:191',
        'cidade' => 'required|string|max:191',
        'estado' => 'required|string|max:2',
        'descricao' => 'required|string|max:191',
        'anexo' => 'nullable|mimes:pdf|max:10000',
    ];

    public $customMessages = [
        'motivo.required_if' => 'O campo motivo é obrigatório quando o campo reembolsável é não.',
    ];

    public function user(){
        return $this->hasOne('Intranet\User', 'id', 'user');
    }

    public $anexosPath = 'app/intranet/pdf/correios/anexos/';
    public $relatoriosPath = 'app/intranet/pdf/correios/relatorios/';
    
    protected $fillable = [
        'reembolsavel', 
        'motivo', 
        'tipo', 
        'pasta', 
        'cliente', 
        'destinatario', 
        'ac', 
        'cep', 
        'rua',
        'numero',
        'complemento',
        'cidade',
        'estado',
        'descricao',
    ];

    function toPdf(){
        
        try {
            $correio = $this;
            
            \PDF::loadView('pdf.correios', compact('correio'))
                ->setPaper('a4', 'landscape')
                ->save(storage_path($this->relatoriosPath . $this->identificador . '.pdf'));

            return true;
        } catch(Exception $e) {
            return false;
        }        
    }

    function toEmail(){
        
        try {

            Mail::send('emails.correios', ['content' => $this], function ($message) {
                $message->from('chebabi@chebabi.adv.br', 'Intranet - Izique Chebabi Advogados');
                $message->to('correio@chebabi.com', $name = null);
                $message->subject('Solcitação de Correio - ' . $this->id);
                if($this->anexo){
                    $message->attach(storage_path($this->anexosPath) . 'Correio_' . $this->identificador . '.pdf');
                }
                $message->attach(storage_path($this->relatoriosPath) . $this->identificador . '.pdf');
            });

            Mail::send('emails.correios_cc', ['content' => $this], function ($message) {
                $message->from('chebabi@chebabi.adv.br', 'Intranet - Izique Chebabi Advogados');
                $message->to(Auth::user()->email, $name = Auth::user()->name);
                $message->subject('Solcitação de Correio - ' . $this->id);
            });

            return true;
        } catch(Exception $e) {
            // TODO - LOG $e
            return false;
        }

        
    }

}
