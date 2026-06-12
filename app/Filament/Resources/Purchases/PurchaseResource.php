<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\ManagePurchases;
use Filament\Resources\Resource;
use App\Models\OrdenCompra;
use BackedEnum; // Importamos el enum para la firma nativa

class PurchaseResource extends Resource
{
    protected static ?string $model = OrdenCompra::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Compras';

    protected static ?string $slug = 'compras';

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return \App\Filament\Resources\Purchases\Tables\PurchaseTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePurchases::route('/'),
        ];
    }
}