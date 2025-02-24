<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['lecturer_id', 'course_id', 'semester_id', 'study_program_id', 'classroom_id'];

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

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function classMembers()
    {
        return $this->hasMany(ClassMember::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_members', 'class_id', 'student_id');
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
