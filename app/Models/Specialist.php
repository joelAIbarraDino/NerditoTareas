<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    
    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function homework(){
        return $this->hasMany(Homework::class, 'specialist');
    }

    public function specialistArea(){
        return $this->belongsTo(SpecialistArea::class, 'area');
    }

    public function paymentSpecialists(){
        return $this->hasMany(PaymentSpecialist::class, 'id_specialist');
    }
}
