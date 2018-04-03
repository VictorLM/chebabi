<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Advogados extends Model
{
    public $rules = [
        'usuario' => 'required|integer|digits_between:1,3|unique:advogados',
        'oab' => 'required|string|max:14|unique:advogados',
        'texto' => 'required|string|max:700',
        'fotoadv' => 'nullable|image|max:300',
        'tipo_adv' => 'required|in:Cível,Trabalhista',
    ];
    
    protected $table = 'advogados';
    
    protected $fillable = ['usuario', 'texto', 'oab', 'foto', 'tipo_adv'];
    
    public function nome_usuario(){
        return $this->hasOne('Intranet\User', 'id', 'usuario');
    }
}
