<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerapiasMatPilatesTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terapias_mat_pilates_turmas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('evento_id', 255)->nullable()->default(null);
            $table->date('dia'); // -feira
            $table->time('inicio_hora');
            $table->time('fim_hora');
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
        Schema::dropIfExists('terapias_mat_pilates_turmas');
    }
}
