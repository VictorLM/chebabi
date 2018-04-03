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
            $table->boolean('kilometragem');
            $table->enum('veiculo', ['ESCRITÓRIO', 'PARTICULAR'])->nullable();
            $table->boolean('reembolsavel');
            $table->boolean('pedagio')->nullable();
            $table->text('clientes');               //SERIALIZE ARRAY - CLIENTES, CONTRÁRIOS, PASTAS, PROCESSOS E DESCRIÇÃO VIAGEM
            $table->text('enderecos');              //SERIALIZE ARRAY
            $table->date('data');
            $table->float('totalkm', 20, 2)->nullable();
            $table->text('despesas')->nullable();   //SERIALIZE ARRAY - CLIENTES, PASTAS, DESCRIÇÕES E VALORES
            $table->float('caucao', 10, 2)->nullable();
            $table->string('observacoes')->nullable();
            $table->boolean('comprovantes')->nullable();
            $table->string('identificador');
            $table->timestamps();
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
