<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules'; 
    protected $fillable = ['class_id', 'day', 'start_time', 'end_time'];

    public function class()
    {
        return $this->belongsTo(LearningClass::class, 'class_id');
    }
}
