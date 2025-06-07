<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary()->comment('Уникальный идентификатор');
            $table->timestamp('date')->notNullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Дата платежа');
            $table->integer('purpose')->nullable()->comment('Цель платежа');
            $table->foreign('purpose')->references('id')->on('dict_payment_purposes')->onDelete('set null');
            $table->text('details')->notNullable()->comment('Детали');
            $table->jsonb('methods')->notNullable()->comment('Методы оплаты (JSON)');
            $table->uuid('receipt_id')->nullable()->comment('Связанный чек');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('set null');
            $table->uuid('created_by')->nullable()->comment('Создатель платежа');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->uuid('doctor_id')->nullable()->comment('Врач, связанный с платежом');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            $table->uuid('patient_id')->nullable()->comment('Пациент, связанный с платежом');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('set null');
            //$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
