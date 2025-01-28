<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyProgram extends Model
{
    use HasFactory;
    
    protected $table = 'study_programs';
    
    protected $fillable = ['faculty_id', 'name'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function studyProgramLecturers()
    {
        return $this->hasMany(StudyProgramLecturer::class);
    }

    public function lecturers()
    {
        return $this->belongsToMany(User::class);
    }
}

