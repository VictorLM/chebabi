<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VagasEstacoesEstacionamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vagas_estacoes_estacionamento', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('alterado_por')->unsigned();
            $table->foreign('alterado_por')->references('id')->on('users');
            $table->integer('estacoes');
            $table->integer('estacionamento');
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
        Schema::dropIfExists('vagas_estacoes_estacionamento');
    }
}
