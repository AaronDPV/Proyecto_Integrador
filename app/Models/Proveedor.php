<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'proveedores';

    protected $fillable = [
        'ruc',
        'razon_social',
        'contacto',
    ];

    public function ordenCompras()
    {
        return $this->hasMany(OrdenCompra::class, 'proveedor_id');
    }
}