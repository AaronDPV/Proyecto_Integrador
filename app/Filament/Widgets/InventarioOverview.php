<?php

namespace App\Filament\Widgets;

use App\Models\Producto;
use Filament\Widgets\Widget;

class InventarioOverview extends Widget
{
    protected string $view = 'filament.widgets.inventario-overview';

    protected int | string | array $columnSpan = 'full';
    protected $listeners = ['refreshTable' => '$refresh'];

    public string $sku = 'PRT-000';
    public int $categoria_id = 1;
    public string $nombre = '';
    public int $stock_actual = 0;
    public int $stock_critico = 5;
    public float $precio_venta = 0.0;

    public function guardarNuevoProductoDesdeModal(): void
    {
        $this->validate([
            'sku' => 'required|string',
            'categoria_id' => 'required|integer',
            'nombre' => 'required|string',
            'stock_actual' => 'required|integer',
            'stock_critico' => 'required|integer',
            'precio_venta' => 'required|numeric',
        ]);

        Producto::create([
            'sku' => $this->sku,
            'categoria_id' => $this->categoria_id,
            'nombre' => $this->nombre,
            'stock_actual' => $this->stock_actual,
            'stock_critico' => $this->stock_critico,
            'precio_venta' => $this->precio_venta,
        ]);

        $this->reset(['nombre', 'stock_actual', 'precio_venta']);
        $this->sku = 'PRT-000';
        $this->categoria_id = 1;
        $this->stock_critico = 5;

        $this->js('isOpen = false');

        $this->dispatch('refreshTable');
    }

    protected function getViewData(): array
    {
        return [
            'stockCritico' => Producto::where('stock_actual', 0)->count(),
            'reabastecimiento' => Producto::whereColumn('stock_actual', '<=', 'stock_critico')->where('stock_actual', '>', 0)->count(),
        ];
    }
}