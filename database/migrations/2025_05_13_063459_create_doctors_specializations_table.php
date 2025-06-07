<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsSpecializationsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors_specializations', function (Blueprint $table) {
            $table->uuid('id_doctor')->comment('ID врача');
            $table->unsignedBigInteger('id_specialization')->comment('ID специализации');
            $table->boolean('is_basic')->default(false)->comment('Основная специализация (true/false)');

            //$table->primary(['id_doctor', 'id_specialization']);

            $table->foreign('id_doctor')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('id_specialization')->references('id')->on('dict_specializations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors_specializations');
    }
}
