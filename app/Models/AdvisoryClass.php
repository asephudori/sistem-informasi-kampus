<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvisoryClass extends Model
{
    protected $fillable = ['lecturer_id', 'class_year'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
