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