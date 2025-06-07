<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique()->comment('Уникальный ID записи');
            $table->text('text')->notNullable()->comment('Текст записи, максимум 120 символов');
            $table->timestamp('created_at')->useCurrent()->comment('Дата создания');
            $table->uuid('created_by')->nullable()->comment('Пользователь, создавший запись');
            $table->timestamp('updated_at')->useCurrent()->comment('Дата последнего обновления');
            $table->uuid('updated_by')->nullable()->comment('Пользователь, обновивший');
            $table->uuid('doctor_schedule')->nullable()->comment('Расписание врача');
            $table->foreign('doctor_schedule')->references('id')->on('doctor_schedules')->onDelete('set null');
            $table->uuid('patient_appointment')->nullable()->comment('Запись на прием пациента');
            $table->foreign('patient_appointment')->references('id')->on('patient_appointments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notes');
    }
}
