<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $table = 'lecturers'; 
    protected $fillable = [
        'user_id', 
        'nidn', 
        'name', 
        'email', 
        'phone', 
        'address', 
        'entry_date', 
        'birthplace', 
        'birthdate', 
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
