<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPurpose extends Model
{
    protected $table = 'dict_payment_purposes';
    public $timestamps = false;
    protected $fillable = ['name'];
}
