<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Terapias_MassagemPes extends Model
{
    protected $table = 'terapias_massagens_pes';

    public function user(){
        return $this->belongsTo('Intranet\User', 'usuario');
    }
}
