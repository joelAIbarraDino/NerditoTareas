<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public function homework(){
        return $this->belongsTo(Homework::class, 'orider_id');
    }


}
