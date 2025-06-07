<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalServicesTable extends Migration
{
    public function up()
    {
        Schema::create('medical_services', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор услуги');
            $table->uuid('pricelist_item_id')->notNullable()->comment('ID услуги из прайс-листа');
            $table->foreign('pricelist_item_id')->references('id')->on('pricelist_items')->onDelete('cascade');
            $table->float('treatment_plan_price')->nullable()->comment('Цена услуги по плану лечения');
            $table->integer('quantity')->notNullable()->default(1)->comment('Количество');
            $table->uuid('performer_id')->nullable()->comment('Врач, выполняющий услугу');
            $table->foreign('performer_id')->references('id')->on('doctors')->onDelete('set null');
            $table->uuid('agent_id')->nullable()->comment('Врач, назначивший услугу (от организма)');
            $table->foreign('agent_id')->references('id')->on('doctors')->onDelete('set null');
            $table->string('conclusion')->nullable()->comment('Заключение');
            $table->uuid('receipt_id')->nullable()->comment('Связанный чек');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('set null');
            $table->timestamp('date')->nullable()->comment('Дата оказания услуги');
            $table->timestamp('created_at')->useCurrent();
            //$table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_services');
    }
}
