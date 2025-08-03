<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricelistItemsSnapshotTable extends Migration
{
    public function up()
    {
        Schema::create('pricelist_items_snapshot', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор записи');
            $table->text('nomenklature')->nullable()->comment('Код по номенклатуре');
            $table->uuid('id_pricelist_item')->nullable()->comment('Ссылка на текущий прайс-лист');
            $table->foreign('id_pricelist_item')->references('id')->on('pricelist_items')->onDelete('set null');

            $table->float('price')->notNullable()->comment('Стоимость услуги');
            $table->float('costprice')->notNullable()->comment('Себестоимость');
            $table->float('fixedsalary')->nullable()->comment('Фиксированная оплата врачу');
            $table->float('fixedagentfee')->nullable()->comment('Фиксированный агентский сбор');

            $table->date('date_start')->nullable()->comment('Дата начала действия');
            $table->date('date_finish')->nullable()->comment('Дата окончания действия');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricelist_items_snapshot');
    }
}
