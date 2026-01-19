<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSpecialist extends Model
{

    public function homework(){
        return $this->belongsTo(Homework::class, 'id_homework');
    }

    public function specialist(){
        return $this->belongsTo(Specialist::class, 'id_specialist');
    }
}
