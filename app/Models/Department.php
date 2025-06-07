<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // Таблица называется 'dict_departments'
    protected $table = 'dict_departments';
    public $timestamps = false;

    // Поля, которые можно заполнять массово
    protected $fillable = ['name'];
}
