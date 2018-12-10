<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable()->unique()->default(null);
            //$table->string('cnpj', 20)->nullable()->default(null);
            $table->string('logo', 200)->nullable()->default(null);
            $table->integer('adv_civel_1')->nullable()->default(null)->unsigned();
            $table->foreign('adv_civel_1')->references('id')->on('users');
            $table->integer('adv_civel_2')->nullable()->default(null)->unsigned();
            $table->foreign('adv_civel_2')->references('id')->on('users');
            $table->integer('adv_civel_3')->nullable()->default(null)->unsigned();
            $table->foreign('adv_civel_3')->references('id')->on('users');
            $table->integer('adv_trab_1')->nullable()->default(null)->unsigned();
            $table->foreign('adv_trab_1')->references('id')->on('users');
            $table->integer('adv_trab_2')->nullable()->default(null)->unsigned();
            $table->foreign('adv_trab_2')->references('id')->on('users');
            $table->integer('adv_trab_3')->nullable()->default(null)->unsigned();
            $table->foreign('adv_trab_3')->references('id')->on('users');
            $table->string('empresas', 2000)->nullable()->default(null);//JSON
            $table->boolean('ativo')->nullable()->default(1);
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
        Schema::dropIfExists('clientes');
    }
}
