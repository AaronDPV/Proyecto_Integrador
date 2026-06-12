<?php

namespace App\Filament\Resources\Users;

use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Resources\Users\Tables\UserTable;
use BackedEnum; 

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function table(Table $table): Table
    {
        return UserTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Users\Pages\ManageUsers::route('/'),
        ];
    }
}