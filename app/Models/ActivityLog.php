<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_name',
        'activity_detail',
        'user_id',
        'times',
        'category',
        'ip_address',
        'device',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}