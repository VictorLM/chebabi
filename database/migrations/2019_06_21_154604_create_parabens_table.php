<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParabensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parabens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('de')->unsigned();
            $table->foreign('de')->references('id')->on('users');
            $table->integer('para')->unsigned();
            $table->foreign('para')->references('id')->on('users');
            $table->string('mensagem', 1000);
            $table->boolean('lido')->default(0);
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
        Schema::dropIfExists('parabens');
    }
}
