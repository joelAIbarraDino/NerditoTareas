<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_provider',
        'amount',
        'payment_id',
        'destination_account',
        'method',
        'status',
    ];

    public function homework(){
        return $this->belongsTo(Homework::class, 'order_id');
    }


}
