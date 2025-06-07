<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentPlansTable extends Migration
{
    public function up()
    {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор');
            $table->uuid('patient')->nullable()->comment('ID пациента');
            $table->foreign('patient')->references('id')->on('patients')->onDelete('set null');
            $table->string('kind')->notNullable()->comment('Тип плана лечения');
            $table->date('starting_date')->notNullable()->default(DB::raw('CURRENT_DATE'))->comment('Дата начала');
            $table->date('expiration_date')->notNullable()->comment('Дата окончания');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('treatment_plans');
    }
}
