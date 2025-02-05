<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;
    
    protected $table = 'schedules'; 
    protected $fillable = ['class_id', 'day', 'start_time', 'end_time'];

    public function class()
    {
        return $this->belongsTo(LearningClass::class, 'class_id');
    }
}
