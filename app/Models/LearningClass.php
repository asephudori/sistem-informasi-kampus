<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['user_id', 'course_id', 'semester_id'];

    public function user()
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
}
