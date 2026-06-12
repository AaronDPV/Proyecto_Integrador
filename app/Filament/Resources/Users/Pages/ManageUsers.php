<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\ManageRecords;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.resources.users.pages.manage-users';

    public function getTitle(): string
    {
        return '';
    }

    public string $currentTab = 'roles';

    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function updateUserRole($userId, $roleId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->role_id = $roleId ?: null;
            $user->save();

            Notification::make()
                ->title('Rol actualizado con éxito')
                ->success()
                ->send();
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            Notification::make()
                ->title('La contraseña actual es incorrecta')
                ->danger()
                ->send();
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        Notification::make()
            ->title('Contraseña actualizada correctamente')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Agregar Usuario')
                ->modalHeading('Registrar Nuevo Usuario')
                ->modalWidth('2xl')
                
                ->modalSubmitActionLabel('Guardar Cambios')
                
                ->createAnother(false)

                ->modalSubmitAction(fn ($action) => $action
                    ->extraAttributes([
                        'style' => 'background-color: #062418 !important; color: white !important; border-radius: 12px !important; font-weight: 600 !important; padding: 8px 20px !important; border: none !important;',
                    ])
                )

                ->modalCancelAction(fn ($action) => $action
                    ->extraAttributes([
                        'style' => 'background-color: #f3f4f6 !important; color: #374151 !important; border-radius: 12px !important; font-weight: 600 !important; padding: 8px 20px !important; border: 1px solid #e5e7eb !important;',
                    ])
                )

                ->extraModalWindowAttributes([
                    'style' => '--primary-600: 6 36 24; --primary-500: 6 36 24; --primary-700: 4 28 19; padding-top: 16px;',
                ])
                ->form(\App\Filament\Resources\Users\Schemas\UserForm::configure()),
        ];
    }
}