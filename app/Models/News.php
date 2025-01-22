<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['admin_id', 'title', 'description', 'image', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

