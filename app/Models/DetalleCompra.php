<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';
    public $timestamps = false;
    protected $fillable = ['orden_compra_id', 'producto_id', 'cantidad', 'precio_unitario'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
