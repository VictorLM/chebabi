<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Parabens extends Model
{
    public $rules = [
        'para' => 'required|integer|digits_between:1,3',
        'mensagem' => 'required|string|max:1000',
    ];
    
    public function de_user(){
        return $this->hasOne('Intranet\User', 'id', 'de');
    }

    public function para_user(){
        return $this->hasOne('Intranet\User', 'id', 'para');
    }
    
    protected $fillable = ['para', 'mensagem'];

    public function notificacao($destinatario){
        Mail::send('emails.parabens', ['content' => $destinatario->name], function ($message) use ($destinatario){
            $message->from('chebabi@chebabi.adv.br', 'Intranet - Izique Chebabi Advogados');
            $message->to($destinatario->email, $name = null);
            $message->subject('Você recebeu uma mensagem de Parabéns!');
        });
    }
}
