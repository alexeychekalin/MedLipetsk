<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLevelTable extends Migration
{
    public function up()
    {
        Schema::create('access_level', function (Blueprint $table) {
            $table->id()->autoIncrement()->comment('Уникальный идентификатор уровня доступа');
            $table->string('name')->notNullable()->unique()->comment('Название уровня доступа');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_level');
    }
}
