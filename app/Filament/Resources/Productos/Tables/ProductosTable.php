<?php

namespace App\Filament\Resources\Productos\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('nombre')->label('Nombre del Producto')->searchable()->sortable(),
                TextColumn::make('categoria.nombre_categoria')->label('Categoría')->searchable(),
                TextColumn::make('stock_actual')->label('Stock Actual')->numeric()->sortable(),
                TextColumn::make('precio_venta')->label('Precio Base')->money('PEN')->sortable(),
            ])
            ->filters([])
            ->recordUrl(null)
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->modalHeading('Editar Producto')
                    ->modalWidth('2xl')
                    ->modalSubmitActionLabel('Guardar Producto')
                    ->extraModalWindowAttributes([
                        'style' => '--primary-600: 6 36 24; --primary-500: 6 36 24; --primary-700: 4 28 19;',
                    ])
                    ->form([
                        \Filament\Schemas\Components\Group::make([
                            TextInput::make('sku')->label('SKU')->placeholder('PRT-000')->required(),
                            Select::make('categoria_id')
                                ->label('Categoría')
                                ->options(\App\Models\Categoria::all()->pluck('nombre_categoria', 'id'))
                                ->required(),
                        ])->columns(2),

                        TextInput::make('nombre')->label('Nombre del Producto')->required(),

                        \Filament\Schemas\Components\Group::make([
                            TextInput::make('stock_actual')->label('Stock Inicial')->numeric()->required(),
                            TextInput::make('stock_critico')->label('Stock Mínimo')->numeric()->required(),
                            TextInput::make('precio_venta')->label('Precio Base')->numeric()->required(),
                        ])->columns(3),
                    ]),
                \Filament\Actions\DeleteAction::make()->iconButton(),
            ]);
    }
}