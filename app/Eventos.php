<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    public $incrementing = false;
    
    public $rules = [

    ];
    
    protected $table = 'eventos';
    
    protected $guarded = [];
}
