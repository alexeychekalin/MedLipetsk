<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('check_templates', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary()->comment('Уникальный идентификатор');
            $table->string('title')->notNullable()->comment('Название шаблона');
            $table->float('discount')->default(0)->notNullable()->comment('Скидка');
            $table->timestamp('created_at')->useCurrent();
            $table->uuid('medical_service')->nullable()->comment('Связь с медицинской услугой');
            $table->foreign('medical_service')->references('id')->on('medical_services')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('check_templates');
    }
}
