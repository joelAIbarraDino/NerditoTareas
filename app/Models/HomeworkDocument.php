<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeworkDocument extends Model
{
    protected $fillable = [
        'id_homework',
        'type',
        'original_name',
        'stored_name',
        'path'
    ];

    public function homework(){
        return $this->belongsTo(Homework::class, 'id_homework');
    }
}
