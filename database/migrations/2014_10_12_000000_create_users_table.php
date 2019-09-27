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
            $table->enum('tipo', ['admin', 'adv', 'adm', 'fin', 'admjur']);
            //$table->enum('tipo', ['admin', 'adv', 'estag', 'adm', 'fin', 'admjur'])->nullable();
            //$table->enum('sub_tipo', ['civel', 'trabalhista', 'adm', 'controladoria', 'socios'])->nullable();
            //$table->enum('escritorio', ['Campinas', 'São Paulo', 'Rio de Janeiro', 'Florianópolis'])->nullable();
            $table->boolean('ativo');
            $table->integer('ramal')->nullable();
            $table->string('telefone')->nullable();
            $table->date('nascimento')->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->unique();
            $table->integer('uaus')->nullable();
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
