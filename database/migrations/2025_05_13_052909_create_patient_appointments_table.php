<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('patient_appointments', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary()->comment('Уникальный идентификатор записи');
            $table->timestamp('scheduled_time')->notNullable()->comment('Запланированное время приема');
            $table->integer('duration')->notNullable()->default('600')->comment('Продолжительность');
            $table->uuid('patient_id')->nullable()->comment('ID пациента');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            $table->string('status')->nullable()->comment('Статус приема');
            $table->timestamp('registration_date')->nullable()->comment('Дата регистрации');
            $table->uuid('registrar')->nullable()->comment('Пользователь, зарегистрировавший');
            $table->foreign('registrar')->references('id')->on('users')->onDelete('set null');
            $table->uuid('receipt_id')->nullable()->comment('Связанный чек');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('set null');
            $table->uuid('schedule_id')->nullable()->comment('Расписание врача');
            $table->foreign('schedule_id')->references('id')->on('doctor_schedules')->onDelete('cascade');
            $table->text('patient_comment')->nullable()->comment('Комментарии пациента');
            $table->boolean('sms_notification_sent')->default(false)->comment('Отправлено SMS');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_appointments');
    }
}
