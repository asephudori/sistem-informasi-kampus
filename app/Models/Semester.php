<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $table = 'semesters';
    protected $fillable = ['name', 'start_periode', 'end_periode'];

    public function learningClasses()
    {
        return $this->hasMany(LearningClass::class);
    }

    public function semesterFees()
    {
        return $this->hasMany(SemesterFee::class);
    }
}
