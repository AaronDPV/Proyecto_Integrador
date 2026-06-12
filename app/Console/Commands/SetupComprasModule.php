<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupComprasModule extends Command
{
    protected $signature = 'erp:setup-compras';
    protected $description = 'Crea la estructura limpia y premium para el modulo de compras en Filament v5';

    public function handle()
    {
        $this->info('Iniciando creación del módulo de compras...');

        // 1. Definir Rutas de Directorios
        $resourcePath = app_path('Filament/Resources/Purchases');
        $pagesPath = $resourcePath . '/Pages';
        $schemasPath = $resourcePath . '/Schemas';
        $tablesPath = $resourcePath . '/Tables';
        $bladePath = resource_path('views/filament/resources/purchases/pages');

        // Crear directorios si no existen
        File::ensureDirectoryExists($pagesPath);
        File::ensureDirectoryExists($schemasPath);
        File::ensureDirectoryExists($tablesPath);
        File::ensureDirectoryExists($bladePath);

        // 2. Crear Archivo: PurchaseForm.php
        $formContent = <<<'PHP'
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
                    ->options(\App\Models\Proveedor::pluck('nombre', 'id'))
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
PHP;
        File::put($schemasPath . '/PurchaseForm.php', $formContent);

        // 3. Crear Archivo: PurchaseTable.php
        $tableContent = <<<'PHP'
<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PurchaseTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_orden')
                    ->label('ID Orden')
                    ->icon('heroicon-o-document-text')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proveedor.nombre') 
                    ->label('Proveedor')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('fecha_emision')
                    ->label('Fecha')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('total_compra')
                    ->label('Total')
                    ->money('USD')
                    ->weight('bold'),

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
            ->actions([
                \Filament\Tables\Actions\Action::make('download')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->iconButton()
                    ->color('gray')
                    ->action(fn ($record) => null),

                \Filament\Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ]);
    }
}
PHP;
        File::put($tablesPath . '/PurchaseTable.php', $tableContent);

        // 4. Crear Archivo: ManagePurchases.php (Página Controladora)
        $pageContent = <<<'PHP'
<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePurchases extends ManageRecords
{
    protected static string $resource = PurchaseResource::class;

    protected string $view = 'filament.resources.purchases.pages.manage-purchases';

    public function getTitle(): string
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Nueva Orden')
                ->modalHeading('Registrar Nueva Orden de Compra')
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
                ->form(\App\Filament\Resources\Purchases\Schemas\PurchaseForm::configure()),
        ];
    }
}
PHP;
        File::put($pagesPath . '/ManagePurchases.php', $pageContent);

        // 5. Crear Archivo: PurchaseResource.php (Recurso Principal)
        $resourceContent = <<<'PHP'
<?php

namespace App\Filament\Resources\Purchases;

use App\Filament\Resources\Purchases\Pages\ManagePurchases;
use Filament\Resources\Resource;
use App\Models\Purchase;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
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
PHP;
        File::put($resourcePath . '/PurchaseResource.php', $resourceContent);

        // 6. Crear la Vista Blade: manage-purchases.blade.php
        $bladeContent = <<<'HTML'
<x-filament-panels::page>
    <style>
        .fi-header, .fi-ac-header-actions {
            display: none !important;
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 20px; font-family: system-ui, sans-serif;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #111827; margin: 0; tracking-tight: -0.025em;">Órdenes de Compra</h1>
                <p style="font-size: 14px; color: #6b7280; margin: 4px 0 0 0;">Gestión de reabastecimiento y proveedores</p>
            </div>
            
            <div>
                <x-filament-actions::modals />
                <button 
                    wire:click="mountAction('create')" 
                    type="button" 
                    style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 24px; background-color: #062418; color: white; font-size: 13px; font-weight: bold; border: none; border-radius: 12px; cursor: pointer; transition: background 0.2s;"
                    onmouseover="this.style.backgroundColor='#03140e'"
                    onmouseout="this.style.backgroundColor='#062418'"
                >
                    + Nueva Orden
                </button>
            </div>
        </div>

        <div class="mt-2">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
HTML;
        File::put($bladePath . '/manage-purchases.blade.php', $bladeContent);

        $this->info('¡Módulo de compras premium creado con éxito en todas sus capas!');
    }
}