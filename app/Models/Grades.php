<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'class_id', 'grade_type_id', 'grade'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class); // Perhatikan: Classes bukan Class
    }

    public function gradeType()
    {
        return $this->belongsTo(GradeTypes::class);
    }
}
