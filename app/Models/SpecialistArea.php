<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialistArea extends Model
{
    public function specialists(){
        return $this->hasMany(Specialist::class, 'area');
    }
}
