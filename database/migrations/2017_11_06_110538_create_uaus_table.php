<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUausTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uaus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('de')->unsigned();
            $table->foreign('de')->references('id')->on('users');
            $table->integer('para')->unsigned();
            $table->foreign('para')->references('id')->on('users');
            $table->text('motivo');
            $table->boolean('lido');
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
        Schema::dropIfExists('uaus');
    }
}
