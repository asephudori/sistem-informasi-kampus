<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvisoryClass extends Model
{
    use HasFactory;
    
    protected $table = 'advisory_classes';
    
    protected $fillable = ['lecturer_id', 'class_year'];

    public function advisoryLecturer()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
