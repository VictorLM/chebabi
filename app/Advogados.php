<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Advogados extends Model
{
    public $rules = [
        'usuario' => 'required|integer|digits_between:1,3|unique:advogados',
        'oab' => 'required|string|max:10|unique:advogados',
        'texto' => 'required|string|max:500',
        'fotoadv' => 'nullable|image|max:300',
    ];
    
    protected $table = 'advogados';
    
    protected $fillable = ['usuario', 'texto', 'oab', 'foto'];
    
    public function nome_usuario(){
        return $this->hasOne('Intranet\User', 'id', 'usuario');
    }
}
