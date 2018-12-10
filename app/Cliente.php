<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    public $rules = [
        'nome' => 'required|string|max:200|unique:clientes',
        'logo' => 'nullable|image|max:300',
        'adv_civel'  => 'nullable|array|max:3',
        'adv_trab'  => 'nullable|array|max:3',
        'empresas' => 'nullable|string|max:2000',
    ];

    protected $guarded = [];

    public function advogado_civel_1(){
        return $this->belongsTo('Intranet\User', 'adv_civel_1');
    }

    public function advogado_civel_2(){
        return $this->belongsTo('Intranet\User', 'adv_civel_2');
    }

    public function advogado_civel_3(){
        return $this->belongsTo('Intranet\User', 'adv_civel_3');
    }

    public function advogado_trab_1(){
        return $this->belongsTo('Intranet\User', 'adv_trab_1');
    }

    public function advogado_trab_2(){
        return $this->belongsTo('Intranet\User', 'adv_trab_2');
    }

    public function advogado_trab_3(){
        return $this->belongsTo('Intranet\User', 'adv_trab_3');
    }
}