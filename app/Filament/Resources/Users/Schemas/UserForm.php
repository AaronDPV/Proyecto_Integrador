<?php

namespace App\Filament\Resources\Users\Schemas;

use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Group; // Tu namespace nativo e indestructible
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class UserForm
{
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Agregar Usuario')
                ->modalHeading('Registrar Nuevo Usuario')
                ->modalWidth('2xl')
                ->extraModalWindowAttributes([
                    'style' => '--primary-600: 6 36 24; --primary-500: 6 36 24; --primary-700: 4 28 19;',
                ])
                ->form(\App\Filament\Resources\Users\Schemas\UserForm::configure()),
        ];
    }
    public static function configure(array $schema = []): array
    {
        return [
            // FILA 1: Nombre y Correo distribuidos simétricamente al 50% cada uno
            Group::make([
                TextInput::make('name')
                    ->label('Nombre Completo')
                    ->placeholder('Ej. Carlos Mendoza')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->placeholder('ejemplo@corporacionportugal.com')
                    ->email()
                    ->required()
                    ->maxLength(191)
                    ->unique(ignoreRecord: true),
            ])->columns(2),

            // FILA 2: Contraseña y Rol distribuidos exactamente igual en la parte inferior
            Group::make([
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->placeholder('••••••••')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state)),

                Select::make('role_id')
                    ->label('Rol Principal')
                    ->placeholder('Selecciona un rol')
                    ->options([
                        1 => 'Administrador',
                        2 => 'Vendedor',
                        3 => 'Inventario',
                    ])
                    ->required(),
            ])->columns(2),
        ];
    }
}