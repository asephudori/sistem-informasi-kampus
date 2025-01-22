<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyLecturer extends Model
{
    protected $table = 'faculty_lecturers'; 
    protected $fillable = ['lecturer_id', 'faculty_id', 'lecturer_position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // TODO: Uncomment kalo udah ada model Faculty
    // public function faculty()
    // {
    //     return $this->belongsTo(Faculty::class);
    // }
}
