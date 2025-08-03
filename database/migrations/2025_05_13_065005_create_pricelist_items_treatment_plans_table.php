<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricelistItemsTreatmentPlansTable extends Migration
{
    public function up()
    {
        Schema::create('pricelist_items_treatment_plans', function (Blueprint $table) {
            $table->uuid('id_pricelist_item')->nullable()->comment('ID услуги из прайс-листа');
            $table->uuid('id_treatment_plan')->nullable()->comment('ID плана лечения');

            $table->foreign('id_pricelist_item')->references('id')->on('pricelist_items')->onDelete('set null');
            $table->foreign('id_treatment_plan')->references('id')->on('treatment_plans')->onDelete('set null');

           // $table->primary(['id_pricelist_item', 'id_treatment_plan']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricelist_items_treatment_plans');
    }
}
