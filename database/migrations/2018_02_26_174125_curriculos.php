<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Curriculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('rg')->nullable();
            $table->string('cpf')->nullable();
            $table->string('endereco')->nullable();
            $table->string('nascimento')->nullable();
            $table->string('area')->nullable();
            $table->string('ip')->nullable();
            $table->mediumText('mensagem')->nullable();
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
        Schema::dropIfExists('curriculos');
    }
}
