<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictPaymentPurposesTable extends Migration
{
    public function up()
    {
        Schema::create('dict_payment_purposes', function (Blueprint $table) {
            $table->id()->comment('Уникальный идентификатор цели платежа');
            $table->string('name')->unique()->notNullable()->comment('Название цели платежа');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dict_payment_purposes');
    }
}
