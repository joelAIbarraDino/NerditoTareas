<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeHomework extends Model
{
    public function homework(){
        return $this->hasMany(Homework::class, 'type_homework');
    }
}
