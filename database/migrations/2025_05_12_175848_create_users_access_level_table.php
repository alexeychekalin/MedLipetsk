<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAccessLevelTable extends Migration
{
    public function up()
    {
        Schema::create('users_access_level', function (Blueprint $table) {
            $table->uuid('id_user')->nullable()->comment('ID пользователя');
            $table->unsignedBigInteger('id_access_level')->nullable()->comment('ID уровня доступа');

            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('id_access_level')->references('id')->on('access_level')->onDelete('set null');
            $table->timestamps();

            // Можно добавить уникальность по паре
           // $table->unique(['id_user', 'id_access_level']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_access_level');
    }
}
