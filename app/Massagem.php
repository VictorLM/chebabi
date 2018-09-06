<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Massagem extends Model
{
    protected $table = 'massagens';

    public function usuario(){
        return $this->belongsTo('Intranet\User', 'usuario');
    }
}
