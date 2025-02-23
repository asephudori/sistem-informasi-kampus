<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = ['name', 'prerequisite_id'];

    public function learningClasses()
    {
        return $this->hasMany(LearningClass::class);
    }

    public function prerequisite()
    {
        return $this->belongsTo(Course::class, 'prerequisite_id');
    }

    public function dependentCourses()
    {
        return $this->hasMany(Course::class, 'prerequisite_id');
    }
}
