<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerapiasMatPilatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terapias_mat_pilates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('evento_id', 255)->nullable()->default(null);
            $table->string('alunos', 1000);
            $table->string('dia');
            $table->time('inicio_hora')->nullable()->default(null);
            $table->time('fim_hora')->nullable()->default(null);
            $table->boolean('cancelado')->nullable()->default(0);
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
        Schema::dropIfExists('terapias_mat_pilates');
    }
}
