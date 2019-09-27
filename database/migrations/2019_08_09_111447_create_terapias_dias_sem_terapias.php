<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerapiasDiasSemTerapias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terapias_dias_sem_terapias', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('tipo', ['quick_massages', 'auriculoterapias', 'massagens_pes', 'massagens_relaxantes', 'mat_pilates']);
            $table->integer('usuario')->unsigned();
            $table->foreign('usuario')->references('id')->on('users');
            $table->date('data')->default(null);
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
        Schema::dropIfExists('terapias_dias_sem_terapias');
    }
}
