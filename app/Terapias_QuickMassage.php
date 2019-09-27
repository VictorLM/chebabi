<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Terapias_QuickMassage extends Model
{
    protected $table = 'terapias_quick_massages';

    public function user(){
        return $this->belongsTo('Intranet\User', 'usuario');
    }

}
