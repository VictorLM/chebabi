<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Relatorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relatorios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario')->unsigned();
            $table->foreign('usuario')->references('id')->on('users');
            $table->dateTime('created_at');
            $table->enum('tipo_viagem', ['Com kilometragem', 'Sem kilometragem']);
            $table->enum('carro', ['Escritório', 'Particular'])->nullable();
            $table->boolean('reembolsavel');
            $table->boolean('pedagio')->nullable();
            $table->string('cliente1');
            $table->string('cliente2')->nullable();
            $table->string('cliente3')->nullable();
            $table->string('contrario1');
            $table->string('contrario2')->nullable();
            $table->string('contrario3')->nullable();
            $table->string('pasta1');
            $table->string('pasta2')->nullable();
            $table->string('pasta3')->nullable();
            $table->string('proc1');
            $table->string('proc2')->nullable();
            $table->string('proc3')->nullable();
            $table->string('enda')->nullable();
            $table->string('endb')->nullable();
            $table->string('endc')->nullable();
            $table->string('endd')->nullable();
            $table->date('data');
            $table->string('motivoviagem1');
            $table->string('motivoviagem2')->nullable();
            $table->string('motivoviagem3')->nullable();
            $table->float('totalkm', 20, 2)->nullable();
            $table->string('descricaodespesa1')->nullable();
            $table->string('descricaodespesa2')->nullable();
            $table->string('descricaodespesa3')->nullable();
            $table->string('descricaodespesa4')->nullable();
            $table->string('despesapasta1')->nullable();
            $table->string('despesapasta2')->nullable();
            $table->string('despesapasta3')->nullable();
            $table->string('despesapasta4')->nullable();
            $table->string('clientedespesa1')->nullable();
            $table->string('clientedespesa2')->nullable();
            $table->string('clientedespesa3')->nullable();
            $table->string('clientedespesa4')->nullable();
            $table->float('despesasgerais1', 10, 2)->nullable();
            $table->float('despesasgerais2', 10, 2)->nullable();
            $table->float('despesasgerais3', 10, 2)->nullable();
            $table->float('despesasgerais4', 10, 2)->nullable();
            $table->float('caucao', 10, 2)->nullable();
            $table->string('observacoes')->nullable();
            $table->string('comprovantes')->nullable();
            $table->string('identificador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relatorios');
    }
}
