<?php

namespace Intranet;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model
{
    public $rules = [
        'tipo_viagem' => 'required|in:Com kilometragem,Sem kilometragem',
        'veiculo' => 'nullable|in:Escritório,Particular',
        'reembolsavel' => 'required|boolean',
        'pedagio' => 'nullable|boolean',
        'cliente1' => 'required|string|max:100',
        'cliente2' => 'nullable|string|max:100',
        'cliente3' => 'nullable|string|max:100',
        'contrario1' => 'required|string|max:100',
        'contrario2' => 'nullable|string|max:100',
        'contrario3' => 'nullable|string|max:100',
        'pasta1' => 'required|string|max:11',
        'pasta2' => 'nullable|string|max:11',
        'pasta3' => 'nullable|string|max:11',
        'proc1' => 'required|string|max:30',
        'proc2' => 'nullable|string|max:30',
        'proc3' => 'nullable|string|max:30',
        'enda' => 'nullable|string|max:150',
        'endb' => 'nullable|string|max:150',
        'endc' => 'nullable|string|max:150',
        'endd' => 'nullable|string|max:150',
        'data' => 'required|date_format:Y-m-d|before_or_equal:today',
        'motivoviagem1' => 'required|string|max:100',
        'motivoviagem2' => 'nullable|string|max:100',
        'motivoviagem3' => 'nullable|string|max:100',
        'totalkm' => 'nullable|string|max:7',//NÃO ENCONTREI VALIDAÇÃO PARA FLOAT
        'descricaodespesa1' => 'nullable|string|max:100',
        'descricaodespesa2' => 'nullable|string|max:100',
        'descricaodespesa3' => 'nullable|string|max:100',
        'descricaodespesa4' => 'nullable|string|max:100',
        'despesapasta1' => 'nullable|string|max:10',
        'despesapasta2' => 'nullable|string|max:10',
        'despesapasta3' => 'nullable|string|max:10',
        'despesapasta4' => 'nullable|string|max:10',
        'clientedespesa1' => 'nullable|string|max:100',
        'clientedespesa2' => 'nullable|string|max:100',
        'clientedespesa3' => 'nullable|string|max:100',
        'clientedespesa4' => 'nullable|string|max:100',
        'despesasgerais1' => 'nullable|string|max:7',//NÃO ENCONTREI VALIDAÇÃO PARA FLOAT
        'despesasgerais2' => 'nullable|string|max:7',//NÃO ENCONTREI VALIDAÇÃO PARA FLOAT
        'despesasgerais3' => 'nullable|string|max:7',//NÃO ENCONTREI VALIDAÇÃO PARA FLOAT
        'despesasgerais4' => 'nullable|string|max:7',//NÃO ENCONTREI VALIDAÇÃO PARA FLOAT
        'caucao' => 'nullable|string|max:7',
        'observacoes' => 'nullable|string|max:200',
        'comprovantes' => 'nullable|mimes:pdf|max:5240',
    ];
    
    public $timestamps = false;
    
    protected $table = 'relatorios';
    
    protected $guarded = [];
    
    public function nome_usuario()
    {
        return $this->hasOne('Intranet\User', 'id', 'usuario');
    }
}
