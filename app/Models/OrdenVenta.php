<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenVenta extends Model
{
    protected $table = 'orden_ventas';
    public $timestamps = false;
    protected $fillable = ['numero_boleta', 'user_id', 'fecha_venta', 'importe_base', 'igv', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'orden_venta_id');
    }
}