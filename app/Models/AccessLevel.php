<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLevel extends Model
{
    protected $table = 'access_level';
    public $timestamps = false;

    protected $fillable = ['name'];
}
