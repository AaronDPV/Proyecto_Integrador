<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. FORZAMOS LA CONEXIÓN Y TU TABLA REAL EN MYSQL
    protected $connection = 'mysql';
    protected $table = 'users';

    // 2. CORRECCIÓN: Desactivamos los campos automáticos created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function ordenVentas(): HasMany
    {
        return $this->hasMany(OrdenVenta::class, 'user_id');
    }
}