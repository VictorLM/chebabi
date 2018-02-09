<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Uau extends Model
{
    public $rules = [
        'para' => 'required|integer|digits_between:1,3',
        'motivo' => 'required|string|max:200',
    ];
    
    public function de_nome(){
        return $this->belongsTo('Intranet\User', 'de');
    }
    public function para_nome(){
        return $this->belongsTo('Intranet\User', 'para');
    }
    
    protected $fillable = ['para', 'motivo'];
}
