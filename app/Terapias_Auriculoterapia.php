<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

/*
De segunda, terça, quinta e sexta
Cada dia em horários diferentes
Limite por usuário de 4 por mês
Prazo de retorno 7 dias
20 minutos por sessão
*/

class Terapias_Auriculoterapia extends Model
{
    protected $table = 'terapias_auriculoterapias';

    public function user(){
        return $this->belongsTo('Intranet\User', 'usuario');
    }
}
