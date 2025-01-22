<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeFormat extends Model
{
    use HasFactory;
    protected $table = 'grade_format';
    protected $fillable = ['min_grade', 'max_grade', 'alphabetical_grade'];
}
