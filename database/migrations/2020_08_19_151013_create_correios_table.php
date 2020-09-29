<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorreiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user')->unsigned();
            $table->foreign('user')->references('id')->on('users');
            $table->date('data');
            $table->boolean('reembolsavel')->default(true);
            $table->string('motivo')->nullable();
            $table->enum('tipo', ['Carta_AR', 'Sedex', 'Sedex_10']);
            $table->string('pasta', 7);
            $table->string('cliente');
            $table->string('destinatario');
            $table->string('ac');
            $table->string('cep', 10);
            $table->string('rua');
            $table->string('numero');
            $table->string('complemento')->nullable()->default(null);
            $table->string('cidade');
            $table->string('estado', 2);
            $table->string('descricao');
            $table->boolean('anexo')->default(false);
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
        Schema::dropIfExists('correios');
    }
}
