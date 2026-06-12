<?php

namespace App\Filament\Resources\Productos;

use App\Filament\Resources\Productos\Pages\CreateProducto;
use App\Filament\Resources\Productos\Pages\EditProducto;
use App\Filament\Resources\Productos\Pages\ListProductos;
use App\Filament\Resources\Productos\Schemas\ProductoForm;
use App\Filament\Resources\Productos\Tables\ProductosTable;
use App\Models\Producto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationLabel = 'Inventario';
    protected static ?string $pluralModelLabel = 'Inventario';
    protected static ?string $modelLabel = 'Inventario';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';


    public static function form(Schema $schema): Schema
    {
        return ProductoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductos::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isInventory() ?? false;
    }
}
