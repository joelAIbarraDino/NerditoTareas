<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function homework(){
        return $this->hasMany(Homework::class, 'client');
    }
}
