<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogArtigos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_artigos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('autor');
            $table->string('titulo');
            $table->string('imagem')->nullable();
            $table->text('descricao');
            $table->string('url');
            $table->enum('categoria', ['Cível', 'Trabalhista', 'Outros']);
            $table->string('tags');
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
        Schema::dropIfExists('blog_artigos');
    }
}
