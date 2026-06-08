<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenVenta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'orden_ventas';

    protected $fillable = [
        'numero_boleta',
        'user_id',
        'fecha_venta',
        'importe_base',
        'igv',
        'total',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'orden_venta_id');
    }
}