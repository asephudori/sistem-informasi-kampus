<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    // Tentukan 'user_id' sebagai primary key
    protected $primaryKey = 'user_id';

    // Jika 'user_id' bukan auto-incrementing
    public $incrementing = false;

    // Tentukan tipe data primary key
    protected $keyType = 'int'; // Sesuaikan dengan tipe data 'user_id'
    
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
