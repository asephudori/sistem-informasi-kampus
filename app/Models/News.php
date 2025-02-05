<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    
    protected $table = 'news';

    protected $fillable = ['admin_id', 'title', 'description', 'image', 'date'];

    public function admin()
    {
        return $this->belongsTo(User::class);
    }
}

