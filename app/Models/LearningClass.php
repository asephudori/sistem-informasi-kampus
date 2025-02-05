<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningClass extends Model
{
    use HasFactory;
    
    protected $table = 'classes';

    protected $fillable = ['lecturer_id', 'course_id', 'semester_id'];

    public function lecturer()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function class_members()
    {
        return $this->hasMany(ClassMember::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
