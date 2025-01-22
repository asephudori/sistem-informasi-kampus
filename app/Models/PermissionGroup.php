<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permission_groups'; 
    protected $fillable = ['permission_id', 'permission_role_id'];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function permissionRole()
    {
        return $this->belongsTo(PermissionRole::class);
    }
}
