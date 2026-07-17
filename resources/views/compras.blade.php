@extends('layouts.app')

@section('title', 'Órdenes de Compra - ERP Portugal')

@section('content')
<div x-data="{ 
    search: '',
    openAddModal: false, 
    openEditModal: false,
    openDeleteModal: false,
    openProvModal: false,
    deleteRoute: '',
    editOrder: { id: '', numero_orden: '', estado: '', notas: '' },
    items: [{ producto_id: '', cantidad: 1, precio_unitario: 0.00 }]
}">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Órdenes de Compra</h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">Gestión de reabastecimiento y proveedores</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="relative w-72">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Buscar Orden o Proveedor..." 
                    class="w-full bg-white border border-slate-200 pl-10 pr-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] placeholder-slate-400 transition-all"
                >
            </div>

            <button @click="openProvModal = true" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-bold py-2.5 px-4 rounded-xl text-sm flex items-center gap-2 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                + Proveedor
            </button>

            <button @click="openAddModal = true" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold py-2.5 px-4 rounded-xl text-sm flex items-center gap-2 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Orden
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm font-medium border border-emerald-100 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50/20">
                        <th class="py-4 px-6 text-center">ID Orden</th>
                        <th class="py-4 px-6 text-center">Proveedor</th>
                        <th class="py-4 px-6 text-center">Fecha</th>
                        <th class="py-4 px-6 text-center">Total</th>
                        <th class="py-4 px-6 text-center">Estado</th>
                        <th class="py-4 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @foreach($orders as $order)
                    <tr 
                        x-show="search === '' || 
                                '{{ strtolower($order->numero_orden) }}'.includes(search.toLowerCase()) || 
                                '{{ strtolower($order->proveedor->razon_social) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50/50 transition-all"
                    >
                        <td class="py-4 px-6 text-center font-bold text-slate-900">
                            {{ $order->numero_orden }}
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-slate-800">{{ $order->proveedor->razon_social }}</td>
                        <td class="py-4 px-6 text-center text-slate-400">{{ \Carbon\Carbon::parse($order->fecha_emision)->format('Y-m-d') }}</td>
                        <td class="py-4 px-6 text-center font-black text-slate-900">${{ number_format($order->total_compra, 2) }}</td>
                        <td class="py-4 px-6 text-center">
                            @if($order->estado === 'Recibido')
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold border border-emerald-100">Recibido</span>
                            @elseif($order->estado === 'Enviado')
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">Enviado</span>
                            @else
                                <span class="bg-slate-50 text-slate-600 px-3 py-1 rounded-full text-xs font-bold border border-slate-100">Borrador</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2" x-data="{ openMenu: false }">
                                <a href="{{ route('compras.pdf', $order->id) }}" class="p-1 text-slate-400 hover:text-slate-600 rounded transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                                
                                <button @click="
                                    editOrder = { 
                                        id: '{{ $order->id }}', 
                                        numero_orden: '{{ $order->numero_orden }}', 
                                        estado: '{{ $order->estado }}',
                                        notas: '{{ $order->notas }}'
                                    }; 
                                    openEditModal = true;" 
                                    class="p-1 text-slate-400 hover:text-slate-600 rounded transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <div class="relative">
                                    <button @click="openMenu = !openMenu" @click.away="openMenu = false" class="p-1 text-slate-400 hover:text-slate-600 rounded transition-all">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    <div x-show="openMenu" class="absolute right-0 mt-1 w-32 bg-white border border-slate-100 rounded-xl shadow-lg z-10 py-1" style="display: none;">
                                        <button @click="deleteRoute = '/compras/' + '{{ $order->id }}'; openDeleteModal = true;" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL REGISTRAR NUEVA ORDEN (BLINDADO CON ANCHO MÁXIMO REAL Y SCROLL INDEPENDIENTE) -->
    <div x-show="openAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-2xl rounded-[1.5rem] p-8 shadow-2xl relative" @click.away="openAddModal = false">
            
            <button @click="openAddModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition-all focus:outline-none z-10">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="mb-5">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Registrar Nueva Orden</h3>
            </div>
            
            <form action="{{ route('compras.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Código de Orden</label>
                    <input type="text" name="numero_orden" required placeholder="ORD-2026-001" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Proveedor</label>
                        <select name="proveedor_id" required class="w-full bg-slate-50/60 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl px-4 py-2 focus:outline-none">
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->razon_social }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Estado</label>
                        <select name="estado" required class="w-full bg-slate-50/60 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl px-4 py-2 focus:outline-none">
                            <option value="Borrador">Borrador</option>
                            <option value="Enviado">Enviado</option>
                            <option value="Recibido">Recibido</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Fecha de Orden</label>
                    <input type="datetime-local" name="fecha_emision" required class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Notas / Justificación de la Compra</label>
                    <textarea name="notas" rows="2" placeholder="Detalla los motivos u observaciones aquí..." class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none resize-none"></textarea>
                </div>

                <div class="border-t border-slate-100 pt-3">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Productos a Solicitar</label>
                    
                    <!-- CONTENEDOR CON ALTO ESTÁTICO BRUTAL PARA ALPINE: El modal no crecerá de aquí -->
                    <div class="space-y-2 h-32 max-h-32 overflow-y-auto pr-1 border border-slate-100 rounded-xl p-2 bg-slate-50/30">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex gap-2 items-center">
                                <select :name="'productos['+index+'][producto_id]'" required class="w-1/2 bg-white border border-slate-200 text-xs rounded-xl px-2 py-1.5 focus:outline-none">
                                    @foreach($productos as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="number" :name="'productos['+index+'][cantidad]'" x-model="item.cantidad" placeholder="Cant" min="1" required class="w-1/4 bg-white border border-slate-200 rounded-xl px-2 py-1 text-xs text-center focus:outline-none">
                                <input type="number" step="0.01" :name="'productos['+index+'][precio_unitario]'" x-model="item.precio_unitario" placeholder="Precio" required class="w-1/4 bg-white border border-slate-200 rounded-xl px-2 py-1 text-xs text-center focus:outline-none">
                                <button type="button" @click="if(items.length > 1) items.splice(index, 1)" class="text-red-500 font-bold px-1 text-xs hover:text-red-700">✕</button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="items.push({ producto_id: '', cantidad: 1, precio_unitario: 0.00 })" class="text-[#051c11] text-xs font-bold mt-2 inline-block hover:underline">+ Añadir Producto</button>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Guardar Orden</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL INSERTAR PROVEEDOR -->
    <div x-show="openProvModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-[380px] rounded-2xl p-6 shadow-2xl relative" @click.away="openProvModal = false">
            <button @click="openProvModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <div class="mb-4">
                <h3 class="text-base font-black text-slate-900 tracking-tight">Insertar Proveedor</h3>
            </div>
            
            <form action="{{ route('proveedores.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Número de RUC (11 dígitos)</label>
                    <input type="text" name="ruc" maxlength="11" minlength="11" required placeholder="10XXXXXXXXX" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Razón Social</label>
                    <input type="text" name="razon_social" required placeholder="Nombre de la empresa" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Contacto / Teléfono (Opcional)</label>
                    <input type="text" name="contacto" placeholder="Ej. Juan Pérez - 999888777" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none">
                </div>
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="openProvModal = false" class="border border-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-4 py-2 rounded-xl text-xs transition-all shadow-sm">Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR ORDEN -->
    <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-[500px] rounded-[1.5rem] p-8 shadow-2xl relative" @click.away="openEditModal = false">
            <button @click="openEditModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mb-6">
                <h3 class="text-lg font-black text-slate-900 tracking-tight" x-text="'Editar Orden: ' + editOrder.numero_orden"></h3>
            </div>
            
            <form :action="'/compras/' + editOrder.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Estado de la Orden</label>
                    <select name="estado" x-model="editOrder.estado" required class="w-full bg-slate-50/60 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl px-4 py-2.5 focus:outline-none">
                        <option value="Borrador">Borrador</option>
                        <option value="Enviado">Enviado</option>
                        <option value="Recibido">Recibido</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Notas / Justificación de la Compra</label>
                    <textarea name="notas" rows="3" x-model="editOrder.notas" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none resize-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CONFIRMACIÓN DE ELIMINACIÓN -->
    <div x-show="openDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-[280px] rounded-2xl p-5 shadow-2xl relative" @click.away="openDeleteModal = false">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-sm font-black text-slate-900 tracking-tight mb-1">¿Confirmar Eliminación?</h3>
                <p class="text-[11px] text-slate-500 font-medium mb-5 leading-relaxed">Esta acción removerá el registro de forma definitiva.</p>
            </div>
            
            <form :action="deleteRoute" method="POST" class="m-0 flex gap-2.5">
                @csrf
                @method('DELETE')
                <button type="button" @click="openDeleteModal = false" class="w-1/2 border border-slate-200 text-slate-700 font-bold py-2 rounded-xl text-xs hover:bg-slate-50 transition-all">
                    Cancelar
                </button>
                <button type="submit" class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-xl text-xs transition-all shadow-sm">
                    Eliminar
                </button>
            </form>
        </div>
    </div>

</div>
@endsection