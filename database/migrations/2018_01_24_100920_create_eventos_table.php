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
            $table->enum('tipo', ['Ausente', 'Motorista', 'Reunião', 'Carro', 'Outro']);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->text('descricao')->nullable();
            $table->string('organizador_nome');
            $table->string('organizador_email');
            $table->string('url', 255);
            $table->boolean('allDay');
            $table->boolean('cancelado');
            $table->string('local')->nullable();
            $table->text('envolvidos');
            $table->string('color')->nullable();
            $table->string('alterado')->nullable();
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
