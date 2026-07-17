<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function tienePermiso(string $permission): bool
    {
        if ($this->role && $this->role->permissions) {
            $permisos = json_decode($this->role->permissions, true);
            return is_array($permisos) && in_array($permission, $permisos);
        }
        return false;
    }
}