<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор');
            $table->float('total_amount')->notNullable()->comment('Общая сумма');
            $table->float('discount')->default(0)->notNullable()->comment('Скидка');
            $table->timestamp('created_at')->useCurrent()->comment('Дата и время создания');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
