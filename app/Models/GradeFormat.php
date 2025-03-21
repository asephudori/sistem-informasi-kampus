<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeFormat extends Model
{
    use HasFactory;
    protected $table = 'grade_format';
    protected $fillable = ['min_grade', 'max_grade', 'alphabetical_grade'];

    public function learningClasses() {
        return $this->belongsToMany(LearningClass::class, 'grade_format_group', 'grade_format_id', 'class_id');
    }
}
