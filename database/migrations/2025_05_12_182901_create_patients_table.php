<?php
/*
 * CREATE INDEXED IN DB
     CREATE INDEX patients_second_name_trgm_idx ON patients USING gin (second_name gin_trgm_ops);
     CREATE INDEX patients_first_name_trgm_idx ON patients USING gin (first_name gin_trgm_ops);
     CREATE INDEX patients_patronymic_name_trgm_idx ON patients USING gin (patronymic_name gin_trgm_ops);
     CREATE INDEX patients_phone_number_trgm_idx ON patients USING gin (phone_number gin_trgm_ops);
 *
 * */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Уникальный идентификатор пациента');
            $table->string('second_name')->nullable()->comment('Фамилия (НЕ зашифровано)');
            $table->string('first_name')->nullable()->comment('Имя (НЕ зашифровано)');
            $table->string('patronymic_name')->nullable()->comment('Отчество (НЕ зашифровано)');
            $table->string('phone_number')->nullable()->comment('Телефон (НЕ зашифровано)');
            $table->float('balance')->notNullable()->default(0);
            $table->uuid('passport')->nullable()->comment('Ссылка на паспорт');
            $table->text('info')->nullable()->comment('Дополнительная информация');
            $table->timestamp('created_at')->useCurrent();
            $table->binary('image')->nullable()->comment('Фото пациента');
            //$table->timestamps();

            $table->foreign('passport')->references('id')->on('passports')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
