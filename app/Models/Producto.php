<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sku',
        'nombre',
        'categoria_id',
        'stock_actual',
        'stock_critico',
        'precio_venta',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id');
    }
}