<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = ['name', 'prequisite'];

    public function learningClasses()
    {
        return $this->hasMany(LearningClass::class);
    }

    public function prequisiteCourse()
    {
        return $this->belongsTo(Course::class, 'prequisite');
    }
}
