<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

class PurchaseTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_orden')->label('ID Orden')->icon('heroicon-o-document-text')->searchable()->sortable(),
                TextColumn::make('proveedor.razon_social')->label('Proveedor')->searchable()->weight('bold'),
                TextColumn::make('fecha_emision')->label('Fecha')->date('Y-m-d')->sortable(),
                TextColumn::make('total_compra')->label('Total')->money('USD')->weight('bold'),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst(strtolower($state)))
                    ->color(fn ($state) => match (strtolower($state)) {
                        'recibido' => 'success',
                        'borrador' => 'gray',
                        'pendiente' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([])
            ->recordUrl(null) 
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->modalHeading('Editar Orden de Compra')
                    ->modalWidth('2xl')
                    ->modalSubmitActionLabel('Guardar Cambios')
                    ->modalSubmitAction(fn ($action) => $action
                        ->extraAttributes(['style' => 'background-color: #062418 !important; color: white !important; border-radius: 12px !important; font-weight: 600 !important; padding: 8px 20px !important; border: none !important;'])
                    )
                    ->modalCancelAction(fn ($action) => $action
                        ->extraAttributes(['style' => 'background-color: #f3f4f6 !important; color: #374151 !important; border-radius: 12px !important; font-weight: 600 !important; padding: 8px 20px !important; border: 1px solid #e5e7eb !important;'])
                    )
                    ->extraModalWindowAttributes([
                        'style' => '--primary-600: 6 36 24; --primary-500: 6 36 24; --primary-700: 4 28 19; padding-top: 16px;',
                    ])
                    ->form([
                        \Filament\Schemas\Components\Group::make([
                            TextInput::make('numero_orden')->label('Número de Orden')->required(),
                            Select::make('proveedor_id')->label('Proveedor')->options(\App\Models\Proveedor::pluck('razon_social', 'id'))->required()->searchable(),
                        ])->columns(2),

                        \Filament\Schemas\Components\Group::make([
                            DateTimePicker::make('fecha_emision')->label('Fecha de Emisión')->required(),
                            Select::make('estado')->label('Estado Inicial')->options(['Borrador' => 'Borrador', 'Pendiente' => 'Pendiente', 'Recibido' => 'Recibido'])->required(),
                        ])->columns(2),

                        TextInput::make('total_compra')->label('Total Compra')->numeric()->prefix('$')->required(),
                    ]),
                \Filament\Actions\DeleteAction::make()->iconButton(),
            ]);
    }
}