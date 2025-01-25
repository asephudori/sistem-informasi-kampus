<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
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

    public function classMembers()
    {
        return $this->hasMany(ClassMember::class);
    }

    public function learningClasses()
    {
        return $this->belongsToMany(LearningClass::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function semesterFees()
    {
        return $this->hasMany(SemesterFee::class);
    }
}
