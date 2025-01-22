<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins'; 
    protected $fillable = ['user_id', 'permission_role_id', 'name', 'role'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permissionRole()
    {
        return $this->belongsTo(PermissionRole::class);
    }
}
