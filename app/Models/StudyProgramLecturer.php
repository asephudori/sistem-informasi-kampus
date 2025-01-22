<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgramLecturer extends Model
{
    protected $table = 'study_program_lecturers'; 
    protected $fillable = ['lecturer_id', 'study_program_id', 'lecturer_position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
