<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->increments('id')->comment('Уникальный идентификатор');
            $table->float('startingcash')->notNullable()->default(0)->comment('Количество наличных в кассе на начало дня');
            $table->date('date_cash')->unique()->comment('Дата, за которую составляется отчет');
            $table->timestamp('created_at')->useCurrent()->comment('Дата создания записи');
            $table->uuid('created_by')->nullable()->comment('Пользователь, создавший отчет');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('report');
    }
}
