<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerapiasMassagensPesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terapias_massagens_pes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('evento_id', 255)->nullable()->default(null);
            $table->integer('usuario')->unsigned();
            $table->foreign('usuario')->references('id')->on('users');
            $table->date('inicio_data')->nullable()->default(null);
            $table->time('inicio_hora')->nullable()->default(null);
            $table->date('fim_data')->nullable()->default(null);
            $table->time('fim_hora')->nullable()->default(null);
            $table->boolean('cancelado')->nullable()->default(0);
            $table->boolean('livre_bonus')->default(0);
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
        Schema::dropIfExists('terapias_massagens_pes');
    }
}
