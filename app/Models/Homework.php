<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    
    protected $fillable = [
        'order_id',
        'private_order_id',
        'name',
        'description',
        'admin',
        'client',
        'conversion',
        'type_homework',
        'client_delivery',
        'specialist_delivery',
        'status',
        'change',
        'specialist',
        'final_price',
        'specialist_payment',
        'proft',
        'amount_paid',
    ];

    public function admin(){
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

    public function orderPayments(){
        return $this->hasMany(OrderPayment::class, 'id_homework');
    }

    public function typeHomework(){
        return $this->belongsTo(TypeHomework::class, 'type_homework');
    }

    public function homeworkDocuments(){
        return $this->hasMany(HomeworkDocument::class, 'id_homework');
    }
}
