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
        Schema::create('dict_departments', function (Blueprint $table) {
            $table->id()->comment('Уникальный идентификатор отделения'); // автоинкрементное primary key
            $table->string('name', 50)->notNullable()->comment('Название отделения');
            $table->timestamps(); // добавит created_at и updated_at
            $table->comment('Справочник отделений');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dict_departments');
    }
};
