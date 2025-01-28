<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassMember extends Model
{
    use HasFactory;
    
    protected $table = 'class_members'; 
    protected $fillable = ['class_id', 'student_id', 'semester_grades'];

    public function class()
    {
        return $this->belongsTo(LearningClass::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
