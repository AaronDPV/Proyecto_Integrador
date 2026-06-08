<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'orden_venta_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function ordenVenta()
    {
        return $this->belongsTo(OrdenVenta::class, 'orden_venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}