<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    
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
