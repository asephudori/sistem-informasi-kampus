<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecturer extends Model
{
    use HasFactory;
    
    protected $table = 'lecturers'; 
    protected $fillable = [
        'user_id', 
        'nidn', 
        'name', 
        'email', 
        'phone', 
        'address', 
        'entry_date', 
        'birthplace', 
        'birthdate', 
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function advisoryClass()
    {
        return $this->hasMany(AdvisoryClass::class);
    }
    
        public function learningClasses()
        {
            return $this->hasMany(LearningClass::class);
        }

    public function studyProgramLecturers()
    {
        return $this->hasMany(StudyProgramLecturer::class);
    }

    public function studyPrograms()
    {
        return $this->belongsToMany(StudyProgram::class);
    }

    public function facultyLecturers()
    {
        return $this->hasMany(FacultyLecturer::class);
    }

    public function faculty()
    {
        return $this->belongsToMany(Faculty::class);
    }
}
