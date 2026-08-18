@extends('layouts.app')

@section('title', 'Inventario Automatizado - ERP Portugal')

@section('content')
<div x-data="{ 
    search: '',
    openAddModal: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}, 
    openReportModal: false, 
    openEditModal: {{ $errors->any() && old('_method') == 'PUT' ? 'true' : 'false' }},
    openDeleteModal: false,
    deleteRoute: '',
    editProduct: { 
        id: '{{ old('id') }}', 
        sku: '{{ old('sku') }}', 
        nombre: '{{ old('nombre') }}', 
        categoria_id: '{{ old('categoria_id') }}', 
        stock_actual: '{{ old('stock_actual', 0) }}', 
        stock_critico: '{{ old('stock_critico', 5) }}', 
        precio_view: '{{ old('precio_view', 0.00) }}' 
    }
}">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Inventario Automatizado</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Gestión de activos, repuestos y control de stock</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-60 lg:w-72">
                <svg class="absolute left-3 top-2.5 h-4 w-4 sm:h-5 sm:w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Buscar SKU o Nombre..." 
                    class="w-full bg-white border border-slate-200 pl-9 sm:pl-10 pr-4 py-2 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] placeholder-slate-400 transition-all"
                >
            </div>

            <button @click="openReportModal = true" class="flex-1 sm:flex-none justify-center bg-white hover:bg-slate-50 border border-slate-200 text-slate-800 font-bold py-2 sm:py-2.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm flex items-center gap-2 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32-2a4 4 0 00-4-4h-1a4 4 0 00-4 4v2m0-10a4 4 0 11-8 0 4 4 0 018 0zM12 14a3 3 0 100-6 3 3 0 000 6zm-7 6h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z" />
                </svg>
                <span class="truncate">Reporte</span>
            </button>

            <button @click="openAddModal = true" class="flex-1 sm:flex-none justify-center bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold py-2 sm:py-2.5 px-3 sm:px-4 rounded-xl text-xs sm:text-sm flex items-center gap-2 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="truncate">Añadir</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 sm:p-5 flex items-start gap-4 shadow-sm">
            <div class="p-3 bg-red-100/60 text-red-600 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-900">Stock Crítico</h3>
                <p class="text-xs text-red-700/80 mt-1 font-medium">{{ $stockCriticoCount }} artículo(s) requiere(n) atención inmediata</p>
            </div>
        </div>

        <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-4 sm:p-5 flex items-start gap-4 shadow-sm">
            <div class="p-3 bg-amber-100/60 text-amber-600 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-amber-900">Reabastecimiento</h3>
                <p class="text-xs text-amber-700/80 mt-1 font-medium">{{ $reabastecimientoCount }} artículo(s) por debajo del mínimo</p>
            </div>
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
                        <th class="py-4 px-6 text-center">SKU</th>
                        <th class="py-4 px-6 text-center">Nombre del Producto</th>
                        <th class="py-4 px-6 w-48 text-center">Categoría</th>
                        <th class="py-4 px-6 w-40 text-center">Stock Actual</th>
                        <th class="py-4 px-6 w-32 text-center">Estado</th>
                        <th class="py-4 px-6 w-32 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @foreach($products as $product)
                    <tr 
                        x-show="search === '' || 
                                '{{ strtolower($product->sku) }}'.includes(search.toLowerCase()) || 
                                '{{ strtolower($product->nombre) }}'.includes(search.toLowerCase())"
                        class="hover:bg-slate-50/50 transition-all"
                    >
                        <td class="py-4 px-6 text-center">
                            <span class="bg-slate-50 text-slate-500 border border-slate-100 px-2.5 py-1 rounded-lg text-xs font-bold tracking-wider">
                                {{ $product->sku }}
                            </span>
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900 text-center">{{ $product->nombre }}</td>
                        <td class="py-4 px-6 text-slate-400 text-center">{{ $product->categoria->nombre_categoria }}</td>
                        <td class="py-4 px-6 whitespace-nowrap text-center">
                            <span class="text-slate-900 font-extrabold">{{ $product->stock_actual }}</span>
                            <span class="text-slate-300 font-normal"> / {{ $product->stock_critico }} min</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($product->stock_actual == 0)
                                <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold border border-red-100">Crítico</span>
                            @elseif($product->stock_actual <= $product->stock_critico)
                                <span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-bold border border-amber-100">Alerta</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold border border-emerald-100">Normal</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2" x-data="{ openMenu: false }">
                                <button @click="
                                    editProduct = { 
                                        id: '{{ $product->id }}', 
                                        sku: '{{ $product->sku }}', 
                                        nombre: '{{ $product->nombre }}', 
                                        categoria_id: '{{ $product->categoria_id }}', 
                                        stock_actual: '{{ $product->stock_actual }}', 
                                        stock_critico: '{{ $product->stock_critico }}', 
                                        precio_view: '{{ $product->precio_view }}' 
                                    }; 
                                    openEditModal = true;" 
                                    class="p-1 text-slate-400 hover:text-slate-600 rounded transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button @click="deleteRoute = '{{ route('inventario.destroy', $product->id) }}'; openDeleteModal = true;" class="p-1 text-slate-400 hover:text-red-600 rounded transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <div class="relative">
                                    <button @click="openMenu = !openMenu" @click.away="openMenu = false" class="p-1 text-slate-400 hover:text-slate-600 rounded transition-all">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    <div x-show="openMenu" class="absolute right-0 mt-1 w-32 bg-white border border-slate-100 rounded-xl shadow-lg z-10 py-1" style="display: none;">
                                        <button @click="editProduct = { id: '{{ $product->id }}', sku: '{{ $product->sku }}', nombre: '{{ $product->nombre }}', categoria_id: '{{ $product->categoria_id }}', stock_actual: '{{ $product->stock_actual }}', stock_critico: '{{ $product->stock_critico }}', precio_view: '{{ $product->precio_view }}' }; openEditModal = true;" class="w-full text-left px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">Editar</button>
                                        <button @click="deleteRoute = '{{ route('inventario.destroy', $product->id) }}'; openDeleteModal = true;" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50">Eliminar</button>
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

    <!-- MODAL AÑADIR PRODUCTO -->
    <div x-show="openAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-[520px] rounded-[1.5rem] p-5 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto" @click.away="openAddModal = false">
            <button @click="openAddModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mb-6">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Añadir Nuevo Producto</h3>
            </div>
            <form action="{{ route('inventario.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" placeholder="PRT-000" class="w-full bg-slate-50/60 border @error('sku') border-red-400 focus:ring-red-100 @else border-slate-200 focus:ring-[#051c11]/10 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-[#051c11] transition-all">
                        @error('sku') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Categoría</label>
                        <select name="categoria_id" class="w-full bg-slate-50/60 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('categoria_id') == $category->id ? 'selected' : '' }}>{{ $category->nombre_categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nombre del Producto</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Descripción detallada" class="w-full bg-slate-50/60 border @error('nombre') border-red-400 focus:ring-red-100 @else border-slate-200 focus:ring-[#051c11]/10 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-[#051c11] transition-all">
                    @error('nombre') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Stock Inicial</label>
                        <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" class="w-full bg-slate-50/60 border @error('stock_actual') border-red-400 focus:ring-red-100 @else border-slate-200 focus:ring-[#051c11]/10 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-[#051c11] transition-all">
                        @error('stock_actual') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Stock Mínimo</label>
                        <input type="number" name="stock_critico" value="{{ old('stock_critico', 5) }}" class="w-full bg-slate-50/60 border @error('stock_critico') border-red-400 focus:ring-red-100 @else border-slate-200 focus:ring-[#051c11]/10 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-[#051c11] transition-all">
                        @error('stock_critico') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Precio Base</label>
                        <input type="number" step="0.01" name="precio_view" value="{{ old('precio_view', 0) }}" class="w-full bg-slate-50/60 border @error('precio_view') border-red-400 focus:ring-red-100 @else border-slate-200 focus:ring-[#051c11]/10 @enderror rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-[#051c11] transition-all">
                        @error('precio_view') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openAddModal = false" class="border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDITAR PRODUCTO -->
    <div x-show="openEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-[520px] rounded-[1.5rem] p-5 sm:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto" @click.away="openEditModal = false">
            <button @click="openEditModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="mb-6">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Editar Producto</h3>
            </div>
            <form :action="'/inventario/' + editProduct.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" x-model="editProduct.id">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">SKU</label>
                        <input type="text" name="sku" required x-model="editProduct.sku" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                        @error('sku') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Categoría</label>
                        <select name="categoria_id" required x-model="editProduct.categoria_id" class="w-full bg-slate-50/60 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nombre_categoria }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nombre del Producto</label>
                    <input type="text" name="nombre" required x-model="editProduct.nombre" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                    @error('nombre') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Stock Actual</label>
                        <input type="number" name="stock_actual" required x-model="editProduct.stock_actual" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                        @error('stock_actual') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Stock Mínimo</label>
                        <input type="number" name="stock_critico" required x-model="editProduct.stock_critico" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                        @error('stock_critico') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Precio Base</label>
                        <input type="number" step="0.01" name="precio_view" required x-model="editProduct.precio_view" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                        @error('precio_view') <p class="text-red-500 text-[11px] font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-all">Cancelar</button>
                    <button type="submit" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL REPORTE GENERAL DE INVENTARIO -->
    <div x-show="openReportModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition style="display: none;">
        <div class="bg-white w-full max-w-[850px] rounded-[1.5rem] shadow-2xl relative flex flex-col max-h-[85vh] overflow-hidden" @click.away="openReportModal = false">
            <div class="bg-[#051c11] text-white p-6 relative flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-bold tracking-tight">Reporte General de Inventario</h3>
                    <p class="text-xs text-emerald-100/70 mt-1.5 flex items-center gap-1.5 font-medium">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Día del Reporte: {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
                <button @click="openReportModal = false" class="text-emerald-200/60 hover:text-white transition-all focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                @foreach($groupedProducts as $categoryName => $items)
                <div class="border border-slate-100 rounded-2xl overflow-hidden">
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#051c11]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Categoría: {{ $categoryName }}</h4>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="text-[10px] font-bold text-slate-400 uppercase bg-slate-50/30 border-b border-slate-100">
                                    <th class="py-3 px-4 w-28 text-center">SKU</th>
                                    <th class="py-3 px-4 text-center">Descripción del Producto</th>
                                    <th class="py-3 px-4 text-center w-36">Stock Mínimo</th>
                                    <th class="py-3 px-4 text-center w-36">Stock Actual</th>
                                    <th class="py-3 px-4 text-center w-36">Valor Unit.</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs font-semibold text-slate-700">
                                @foreach($items as $item)
                                <tr class="border-b border-slate-100/50 last:border-0">
                                    <td class="py-3.5 px-4 text-slate-400 font-medium text-center">{{ $item->sku }}</td>
                                    <td class="py-3.5 px-4 text-slate-900 font-bold text-center">{{ $item->nombre }}</td>
                                    <td class="py-3.5 px-4 text-center text-slate-400">{{ $item->stock_critico }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="{{ $item->stock_actual == 0 ? 'text-red-600 font-black' : ($item->stock_actual <= $item->stock_critico ? 'text-red-500 font-bold' : 'text-slate-900 font-extrabold') }}">
                                            {{ $item->stock_actual }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center text-slate-500">${{ number_format($item->precio_view, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="p-6 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                <span class="text-xs font-bold text-slate-500">Total de Categorías: {{ $groupedProducts->count() }} | Total SKUs: {{ $products->count() }}</span>
                <div class="flex items-center gap-3">
                    <button @click="openReportModal = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm transition-all">Cerrar Vista</button>
                    <a href="{{ route('inventario.pdf') }}" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2 no-underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Exportar PDF
                    </a>
                </div>
            </div>
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