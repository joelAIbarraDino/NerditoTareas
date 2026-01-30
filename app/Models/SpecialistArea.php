<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialistArea extends Model
{
    protected $fillable = [
        'name'
    ];

    public function specialists(){
        return $this->hasMany(Specialist::class, 'area');
    }
}
