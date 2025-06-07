<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'report';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'startingcash',
        'date_cash',
        'created_by',
    ];

    public function payments()
    {
        return $this->hasMany(Payments::class);
    }
}
