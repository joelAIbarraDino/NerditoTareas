<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    
    public function user(){
        return $this->belongsTo(User::class, 'admin');
    }

    public function client(){
        return $this->belongsTo(Client::class, 'client');
    }

    public function specialist(){
        return $this->belongsTo(Specialist::class, 'specialist');
    }

    public function paymentSpecialists(){
        return $this->hasMany(PaymentSpecialist::class, 'id_homework');
    }

    public function payments(){
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function typeHomework(){
        return $this->belongsTo(TypeHomework::class, 'type_homework');
    }
}
