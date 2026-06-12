<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class UserTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                TextColumn::make('role_id')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Administrador',
                        2 => 'Vendedor',
                        3 => 'Inventario',
                        default => 'Sin Rol',
                    })
                    ->color(fn ($state) => match ($state) {
                        1 => 'danger',
                        2 => 'success',
                        3 => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                // Tus filtros...
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->iconButton()
                    ->color('warning')
                    ->modalHeading('Editar Usuario')
                    ->modalWidth('2xl')
                    ->modalSubmitActionLabel('Guardar Cambios')
                    
                    ->extraModalWindowAttributes([
                        'style' => '--primary-600: 6 36 24; --primary-500: 6 36 24; --primary-700: 4 28 19;',
                    ])
                    ->form(\App\Filament\Resources\Users\Schemas\UserForm::configure()),

                \Filament\Actions\DeleteAction::make()
                    ->iconButton(),
            ]);
    }
}