<?php

namespace App\Filament\Resources\Productos\Pages;

use App\Filament\Resources\Productos\ProductoResource;
use App\Filament\Widgets\InventarioOverview;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action; 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Producto;

class ListProductos extends ListRecords
{
    protected static string $resource = ProductoResource::class;
    protected $listeners = ['refreshTable' => '$refresh'];

    public function getMaxContentWidth(): string | null
    {
        return 'full'; 
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InventarioOverview::class,
        ];
    }
}