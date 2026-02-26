<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSpecialist extends Model
{
    protected $fillable = [
        'id_specialist',
        'id_homework',
        'amount',
        'type'
    ];

    public function homework(){
        return $this->belongsTo(Homework::class, 'id_homework');
    }

    public function specialist(){
        return $this->belongsTo(Specialist::class, 'id_specialist');
    }
}
