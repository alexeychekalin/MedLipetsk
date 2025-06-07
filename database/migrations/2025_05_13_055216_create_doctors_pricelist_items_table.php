<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsPricelistItemsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors_pricelist_items', function (Blueprint $table) {
            $table->uuid('id_doctor')->comment('ID врача');
            $table->uuid('id_pricelist_item')->comment('ID услуги из прайс-листа');
            $table->boolean('is_basic')->default(false)->comment('Основная услуга (true/false)');

            //$table->primary(['id_doctor', 'id_pricelist_item']);

            $table->foreign('id_doctor')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('id_pricelist_item')->references('id')->on('pricelist_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors_pricelist_items');
    }
}
