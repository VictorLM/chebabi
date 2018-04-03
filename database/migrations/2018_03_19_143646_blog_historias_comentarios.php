<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogHistoriasComentarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_historias_comentarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('autor');
            $table->string('email');
            $table->text('comentario');
            $table->integer('historia')->unsigned();
            $table->foreign('historia')->references('id')->on('blog_historias');
            $table->string('infos_sessao', 255)->nullable();
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
        Schema::dropIfExists('blog_historias_comentarios');
    }
}
