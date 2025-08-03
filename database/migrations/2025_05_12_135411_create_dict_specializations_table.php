<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dict_specializations', function (Blueprint $table) {
            $table->id()->comment('Уникальный идентификатор специализации');
            $table->string('name', 50)->notNullable()->comment('Название специализации');
            $table->timestamps();
            $table->comment('Справочник специализаций');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dict_specializations');
    }
};
