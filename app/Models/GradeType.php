<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeType extends Model
{
    use HasFactory;
    protected $table = 'grade_type';
    protected $fillable = ['name', 'percentage'];

}
