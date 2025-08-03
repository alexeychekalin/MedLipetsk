<?php

/*
 * CREATE INDEXEX IN DB
 *
    CREATE INDEX doctor_second_name_trgm_idx ON doctors USING gin (second_name gin_trgm_ops);
    CREATE INDEX doctor_first_name_trgm_idx ON doctors USING gin (first_name gin_trgm_ops);
    CREATE INDEX doctor_patronymic_name_trgm_idx ON doctors USING gin (patronymic_name gin_trgm_ops);
    CREATE INDEX doctor_phone_number_trgm_idx ON doctors USING gin (phone_number gin_trgm_ops);
 *
 * */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор врача');
            $table->string('second_name')->nullable()->comment('Фамилия (НЕ зашифровано)');
            $table->string('first_name')->nullable()->comment('Имя (НЕ зашифровано)');
            $table->string('patronymic_name')->nullable()->comment('Отчество (НЕ зашифровано)');
            $table->string('phone_number')->unique()->notNullable()->comment('Телефон (НЕ зашифровано)');
            $table->date('birth_date')->notNullable()->comment('Дата рождения');
            $table->unsignedBigInteger('department')->nullable()->comment('Отделение');
            $table->foreign('department')->references('id')->on('dict_departments')->onDelete('set null');
            $table->integer('service_duration')->notNullable()->default('600')->comment('Длительность приема');
            $table->integer('default_cabinet')->notNullable()->default(1)->comment('Кабинет');
            $table->float('balance')->notNullable()->default(0)->comment('Баланс');
            $table->text('info')->nullable()->comment('Доп. информация');
           // $table->timestamp('created_at')->useCurrent();
            $table->binary('image')->nullable()->comment('Фото врача');
            $table->uuid('id_user')->nullable()->comment('Пользовательская учетная запись');
            $table->jsonb('vacation_schedule')->default('[]')->comment('График отпусков');
            $table->uuid('as_patient')->nullable()->comment('Связь с пациентом (если есть)');
            $table->float('rating')->default(0)->comment('Рейтинг врача');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            $table->foreign('as_patient')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
