<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $fillable = ['student_id', 'class_id', 'grade_type_id', 'grade'];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function learningClass()
    {
        return $this->belongsTo(LearningClass::class, 'class_id');
    }

    public function gradeType()
    {
        return $this->belongsTo(GradeType::class);
    }
}
