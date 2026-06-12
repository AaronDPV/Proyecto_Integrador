<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePurchases extends ManageRecords
{
    protected static string $resource = PurchaseResource::class;

    // Propiedad dinámica estándar
    protected string $view = 'filament.resources.purchases.pages.manage-purchases';

    public function getTitle(): string
    {
        return ''; // Matamos el título nativo para usar tu cabecera custom
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Nueva Orden')
                ->modalHeading('Registrar Nueva Orden de Compra')
                ->modalWidth('2xl')
                ->modalSubmitActionLabel('Guardar Cambios') // Método oficial v5
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
                ->form(\App\Filament\Resources\Purchases\Schemas\PurchaseForm::configure()),
        ];
    }
}