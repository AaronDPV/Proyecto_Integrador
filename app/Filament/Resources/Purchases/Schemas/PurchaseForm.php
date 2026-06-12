<?php

namespace App\Filament\Resources\Purchases\Schemas;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

class PurchaseForm
{
    public static function configure(array $schema = []): array
    {
        return [
            Group::make([
                TextInput::make('numero_orden')
                    ->label('Número de Orden')
                    ->placeholder('Ej. ORD-2026-001')
                    ->required()
                    ->maxLength(100),

                Select::make('proveedor_id')
                    ->label('Proveedor')
                    ->placeholder('Selecciona un proveedor')
                    
                    ->options(\App\Models\Proveedor::pluck('razon_social', 'id')) 
                    
                    ->required()
                    ->searchable(),
            ])->columns(2),

            Group::make([
                DateTimePicker::make('fecha_emision')
                    ->label('Fecha de Emisión')
                    ->required()
                    ->default(now()),

                Select::make('estado')
                    ->label('Estado Inicial')
                    ->options([
                        'Borrador' => 'Borrador',
                        'Pendiente' => 'Pendiente',
                        'Recibido' => 'Recibido',
                    ])
                    ->required()
                    ->default('Borrador'),
            ])->columns(2),

            Group::make([
                TextInput::make('total_compra')
                    ->label('Total Compra')
                    ->numeric()
                    ->prefix('$')
                    ->placeholder('0.00')
                    ->required(),
            ])->columns(2),
        ];
    }
}