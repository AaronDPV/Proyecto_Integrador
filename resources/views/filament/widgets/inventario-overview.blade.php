<div x-data="{ isOpen: false }" style="width: 100%; font-family: system-ui, -apple-system, sans-serif; box-sizing: border-box; margin-bottom: 0.5rem; padding: 0; position: relative;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 2rem; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: #111827; margin: 0; letter-spacing: -0.03em;">Inventario Automatizado</h1>
            <p style="font-size: 0.9rem; font-weight: 500; color: #8892b0; margin: 0.35rem 0 0 0;">Gestión de activos, repuestos y control de stock</p>
        </div>
        
        <div style="display: flex; gap: 0.75rem; align-items: center; white-space: nowrap;">
            <button onclick="window.print()" style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: #ffffff; color: #374151; font-size: 0.875rem; font-weight: 600; padding: 0.65rem 1.25rem; border: 1px solid #d1d5db; border-radius: 0.6rem; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                <span style="font-size: 1rem;">📋</span> Generar Reporte
            </button>
            
            <button x-on:click="isOpen = true" style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: #0c2317; color: #ffffff; font-size: 0.875rem; font-weight: 600; padding: 0.65rem 1.25rem; border: none; border-radius: 0.6rem; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                <span style="font-size: 1.1rem; line-height: 1;">+</span> Añadir Producto
            </button>
        </div>
    </div>

    <div style="display: flex; gap: 1.25rem; width: 100%; box-sizing: border-box; justify-content: space-between; flex-wrap: wrap; margin-bottom: 2rem;">
        <div style="flex: 1; min-width: 280px; background-color: #fffdfd; border: 1px solid #fecaca; border-left: 5px solid #dc2626; border-radius: 0.8rem; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1rem;">
            <div style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; min-width: 2.25rem; border-radius: 50%; background-color: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; font-weight: 800; font-size: 1.1rem;">!</div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 1.05rem; font-weight: 700; color: #1f2937; letter-spacing: -0.01em;">Stock Crítico</span>
                <span style="font-size: 0.85rem; font-weight: 600; color: #dc2626; margin-top: 0.15rem;">{{ $stockCritico }} artículo(s) requiere(n) atención inmediata</span>
            </div>
        </div>

        <div style="flex: 1; min-width: 280px; background-color: #fffbeb; border: 1px solid #fef3c7; border-left: 5px solid #ea580c; border-radius: 0.8rem; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1rem;">
            <div style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; min-width: 2.25rem; border-radius: 50%; background-color: #fffbeb; border: 1px solid #fef3c7; color: #ea580c; font-weight: 800; font-size: 1.1rem;">!</div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 1.05rem; font-weight: 700; color: #1f2937; letter-spacing: -0.01em;">Reabastecimiento</span>
                <span style="font-size: 0.85rem; font-weight: 600; color: #b45309; margin-top: 0.15rem;">{{ $reabastecimiento }} artículo(s) por debajo del mínimo</span>
            </div>
        </div>
    </div>

    <div x-show="isOpen" x-transition.opacity x-cloak style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100vw !important; height: 100vh !important; background-color: rgba(17, 24, 39, 0.7); backdrop-filter: blur(4px); z-index: 99998 !important;"></div>

    <div x-show="isOpen" 
         x-transition.scale.95 
         x-cloak 
         style="position: fixed !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; width: 100% !important; max-width: 550px !important; background-color: #ffffff; border-radius: 1rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); overflow: hidden; font-family: system-ui, -apple-system, sans-serif; z-index: 99999 !important; box-sizing: border-box;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f3f4f6;">
            <h2 style="font-size: 1.2rem; font-weight: 800; color: #111827; margin: 0;">Crear Nuevo Producto</h2>
            <button type="button" x-on:click="isOpen = false" style="background: none; border: none; color: #9ca3af; font-size: 1.25rem; cursor: pointer; font-weight: 600;" onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#9ca3af'">✕</button>
        </div>

        <form wire:submit.prevent="guardarNuevoProductoDesdeModal" style="margin: 0; padding: 1.5rem;">
            
            <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">SKU</label>
                    <input type="text" wire:model="sku" required style="width: 100%; padding: 0.65rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; color: #1f2937; box-shadow: inset 0 1px 2px rgba(0,0,0,0.02); box-sizing: border-box;">
                </div>
                
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">Categoría</label>
                    <select wire:model="categoria_id" required style="width: 100%; padding: 0.55rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; color: #1f2937; background-color: #ffffff; cursor: pointer; box-sizing: border-box; height: 38px; line-height: 1.2; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 0.65rem auto; padding-right: 2rem;">
                        @foreach(\App\Models\Categoria::all() as $cat)
                            <option value="{{ $cat->id }}">
                                {{ $cat->nombre_categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">Nombre del Producto</label>
                <input type="text" wire:model="nombre" placeholder="Descripción detallada" required style="width: 100%; padding: 0.65rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; color: #1f2937; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 0.75rem; margin-bottom: 1.75rem;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">Stock Inicial</label>
                    <input type="number" wire:model="stock_actual" required style="width: 100%; padding: 0.65rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; color: #1f2937; box-sizing: border-box;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">Stock Mínimo</label>
                    <input type="number" wire:model="stock_critico" required style="width: 100%; padding: 0.65rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; color: #1f2937; box-sizing: border-box;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #374151; margin-bottom: 0.4rem;">Precio Base</label>
                    <input type="number" wire:model="precio_venta" required style="width: 100%; padding: 0.65rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; color: #1f2937; box-sizing: border-box;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #f3f4f6; padding-top: 1.25rem;">
                <button type="button" x-on:click="isOpen = false" style="padding: 0.65rem 1.25rem; border: 1px solid #d1d5db; background-color: #ffffff; color: #374151; font-weight: 600; font-size: 0.875rem; border-radius: 0.5rem; cursor: pointer; transition: background 0.2s;">Cancelar</button>
                <button type="submit" style="padding: 0.65rem 1.25rem; border: none; background-color: #0c2317; color: #ffffff; font-weight: 600; font-size: 0.875rem; border-radius: 0.5rem; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: background 0.2s;">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>