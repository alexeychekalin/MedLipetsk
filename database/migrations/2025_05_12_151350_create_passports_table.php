<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassportsTable extends Migration
{
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор паспорта');
            $table->string('series_number')->notNullable()->comment('Серия и номер паспорта (зашифровано)');
            $table->string('authority')->notNullable()->comment('Орган, выдавший паспорт (зашифровано)');
            $table->string('gender', 10)->nullable()->comment('Пол пациента, например "M" или "F"');
            $table->date('birthday')->notNullable()->comment('Дата рождения пациента');
            $table->date('issue_date')->notNullable()->comment('Дата выдачи паспорта');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('passports');
    }
}
