<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary()->comment('Уникальный идентификатор пользователя');
            $table->string('login')->unique()->notNullable()->comment('Логин пользователя');
            $table->string('password')->notNullable()->comment('Пароль пользователя (зашифрован, например, bcrypt)');
            $table->string('second_name')->nullable()->comment('Фамилия в зашифрованном виде');
            $table->string('first_name')->nullable()->comment('Имя в зашифрованном виде');
            $table->string('patronymic_name')->nullable()->comment('Отчество в зашифрованном виде');
            $table->string('post')->nullable()->comment('Должность пользователя');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
