<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students'; // Optional since Laravel assumes plural form
    protected $fillable = [
        'user_id', 
        'advisory_class_id', 
        'study_program_id', 
        'nim', 
        'name', 
        'email', 
        'phone', 
        'birthplace', 
        'birthdate', 
        'home_address', 
        'current_address', 
        'home_city_district', 
        'home_postal_code', 
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advisoryClass()
    {
        return $this->belongsTo(AdvisoryClass::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function learningClasses()
    {
        return $this->hasMany(ClassMember::class);
    }
}
