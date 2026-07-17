<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = ['nombre_categoria'];

    // Relación: Una categoría tiene muchos productos
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}