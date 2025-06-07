<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesSnapshotTable extends Migration
{
    public function up()
    {
        Schema::create('salaries_snapshot', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор записи');
            $table->string('type')->notNullable()->comment('Тип зарплаты: Сдельная, Ежемесячная, Почасовая');
            $table->float('rate')->nullable()->comment('Процентная ставка для сдельной');
            $table->integer('monthly_amount')->nullable()->comment('Фиксированная ежемесячная сумма');
            $table->integer('hourly_amount')->nullable()->comment('Почасовая сумма');
            $table->uuid('doctor')->nullable()->comment('ID врача');
            $table->foreign('doctor')->references('id')->on('doctors')->onDelete('cascade');

            $table->date('date_start')->nullable()->comment('Дата начала');
            $table->date('date_finish')->nullable()->comment('Дата окончания');

            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries_snapshot');
    }
}
