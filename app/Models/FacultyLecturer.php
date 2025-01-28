<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacultyLecturer extends Model
{
    use HasFactory;
    
    protected $table = 'faculty_lecturers'; 
    protected $fillable = ['lecturer_id', 'faculty_id', 'lecturer_position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
