<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    public $timestamps = false;
    protected $fillable = ['ruc', 'razon_social', 'contacto'];

    public function ordenes()
    {
        return $this->hasMany(OrdenCompra::class, 'proveedor_id');
    }
}
