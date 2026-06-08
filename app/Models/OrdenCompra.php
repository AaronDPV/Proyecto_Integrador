<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'orden_compras';

    protected $fillable = [
        'numero_orden',
        'proveedor_id',
        'fecha_emision',
        'estado',
        'total_compra',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}