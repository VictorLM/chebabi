<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAndamentoDataCloudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('andamento_data_clouds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pasta')->nullable();
            $table->integer('pasta_id')->nullable();
            $table->dateTime('data_cadastro_pasta')->nullable();
            $table->string('processo')->nullable();
            $table->string('cliente')->nullable();
            $table->string('posicao')->nullable();
            $table->string('contrario')->nullable();
            $table->string('area')->nullable();
            $table->mediumText('descricao');
            $table->dateTime('last_sync');
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
        Schema::dropIfExists('andamento_data_clouds');
    }
}
