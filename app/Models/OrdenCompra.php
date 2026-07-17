<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $table = 'orden_compras';
    public $timestamps = false;
    protected $fillable = ['numero_orden', 'proveedor_id', 'fecha_emision', 'estado', 'total_compra', 'notas'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'orden_compra_id');
    }
}