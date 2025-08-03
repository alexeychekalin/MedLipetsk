<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricelistItemsTable extends Migration
{
    public function up()
    {
        Schema::create('pricelist_items', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary()->comment('Уникальный идентификатор услуги в прайс-листе');
            $table->text('nomenklature')->nullable()->comment('Код по номенклатуре медицинских услуг');
            $table->unsignedBigInteger('category')->nullable()->comment('Отделение, связанное с услугой');
            $table->foreign('category')->references('id')->on('dict_departments')->onDelete('set null');
            $table->text('title')->notNullable()->comment('Название услуги');
            $table->float('price')->notNullable()->comment('Стоимость услуги');
            $table->float('costprice')->notNullable()->comment('Себестоимость услуги');
            $table->boolean('archived')->default(false)->comment('Архивный статус услуги');
            $table->float('fixedsalary')->nullable()->comment('Фиксированная оплата врачу за услугу');
            $table->float('fixedagentfee')->nullable()->comment('Фиксированный агентский сбор');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricelist_items');
    }
}
