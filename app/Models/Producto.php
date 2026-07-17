<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    protected $table = 'productos';

    public $timestamps = false;

    protected $fillable = [
        'sku',
        'nombre',
        'categoria_id',
        'stock_actual',
        'stock_critico',
        'precio_view'
    ];

    // Relación: El producto pertenece a una categoría
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }
}