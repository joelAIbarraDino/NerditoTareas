<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'id_homework',
        'amount',
        'mp_link',
        'status'
    ];
}
