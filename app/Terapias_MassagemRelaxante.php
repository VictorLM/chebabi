<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Terapias_MassagemRelaxante extends Model
{
    protected $table = 'terapias_massagens_relaxantes';

    public function user(){
        return $this->belongsTo('Intranet\User', 'usuario');
    }
}
