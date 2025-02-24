<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeFormatGroup extends Model
{
    protected $table = 'grade_format_group';

    protected $fillable = ['class_id, grade_format_id'];

    public function learningClasses() {
        return $this->belongsTo(LearningClass::class);
    }

    public function gradeFormat() {
        return $this->belongsTo(GradeFormat::class);
    }
}
