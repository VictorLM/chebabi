<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->enum('tipo', ['Ausente', 'Motorista', 'Reunião', 'Carro', 'Outro'])->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->text('descricao')->nullable();
            $table->string('organizador_nome');
            $table->string('organizador_email');
            $table->string('url', 255);
            $table->boolean('allDay');
            $table->boolean('cancelado');
            $table->string('local')->nullable();
            $table->string('envolvido1_nome');
            $table->string('envolvido1_email');
            $table->string('envolvido2_nome')->nullable();
            $table->string('envolvido2_email')->nullable();
            $table->string('envolvido3_nome')->nullable();
            $table->string('envolvido3_email')->nullable();
            $table->string('envolvido4_nome')->nullable();
            $table->string('envolvido4_email')->nullable();
            $table->dateTime('last_sync')->nullable();
            $table->string('color')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
