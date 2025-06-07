<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор записи');
            $table->uuid('doctor_id')->notNullable()->comment('ID врача');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->integer('cabinet')->notNullable()->default(1)->comment('Кабинет');
            $table->timestamp('starting')->notNullable()->comment('Время начала');
            $table->timestamp('ending')->notNullable()->comment('Время окончания');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_schedules');
    }
}
