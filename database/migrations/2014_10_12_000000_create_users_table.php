<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->enum('tipo', ['admin', 'adv', 'estag', 'adm', 'fin', 'admjur'])->nullable()->default(null);
            $table->enum('sub_tipo', ['civel', 'trabalhista', 'adm', 'controladoria', 'socios'])->nullable()->default(null);
            $table->enum('localidade', ['Campinas', 'São Paulo', 'Rio de Janeiro', 'Florianópolis', 'Home Office', 'Outra'])->nullable()->default(null);
            $table->boolean('ativo');
            $table->integer('ramal')->nullable();
            $table->string('telefone')->nullable();
            $table->date('nascimento')->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->unique();
            $table->dateTime('last_login')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
