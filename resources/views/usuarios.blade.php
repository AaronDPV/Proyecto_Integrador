@extends('layouts.app')

@section('title', 'Seguridad y Roles (RBAC) - ERP Portugal')

@section('content')
<div x-data="{ tab: 'roles', openModal: false }">
    
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Seguridad y Roles (RBAC)</h1>
        <p class="text-sm text-slate-500 mt-1 font-medium">Gestión de accesos y configuración de cuenta</p>
    </div>

    <div class="flex items-center gap-6 border-b border-slate-200 mb-8">
        <button @click="tab = 'roles'" 
            :class="tab === 'roles' ? 'border-[#051c11] text-[#051c11] font-bold' : 'border-transparent text-slate-400 font-medium hover:text-slate-600'"
            class="flex items-center gap-2 pb-4 border-b-2 text-sm transition-all focus:outline-none">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Gestión de Roles
        </button>

        <button @click="tab = 'seguridad'" 
            :class="tab === 'seguridad' ? 'border-[#051c11] text-[#051c11] font-bold' : 'border-transparent text-slate-400 font-medium hover:text-slate-600'"
            class="flex items-center gap-2 pb-4 border-b-2 text-sm transition-all focus:outline-none">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Seguridad
        </button>
    </div>

    <div x-show="tab === 'roles'" x-transition class="space-y-6">
        
        <div class="flex justify-end mb-4">
            <button @click="openModal = true" class="bg-[#051c11] hover:bg-[#082a1a] text-white font-bold py-2 px-4 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Usuario
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm font-medium border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium border border-red-100">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            @foreach($users as $user)
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#051c11] text-white flex items-center justify-center font-bold text-base shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-tight">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($user->id !== auth()->id())
                        <form action="/usuarios/{{ $user->id }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar permanentemente a este usuario del sistema?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 rounded-xl hover:bg-red-50 transition-all" title="Eliminar Usuario">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg uppercase tracking-wider">
                            Tu Cuenta
                        </span>
                    @endif
                </div>

                <form action="/usuarios/{{ $user->id }}/actualizar-accesos" method="POST" id="form-accesos-{{ $user->id }}">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center gap-3 text-sm pt-2">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider w-32">Rol Principal:</span>
                        <select name="role_id" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm font-semibold rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all cursor-pointer">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->nombre_rol }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php 
                        $userPerms = json_decode($user->role->permissions, true) ?? [];
                    @endphp
                    <div class="pt-4 border-t border-slate-50 mt-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Permisos Granulares Actuales</h4>
                            <button type="submit" class="bg-slate-100 hover:bg-[#051c11] hover:text-white text-slate-700 font-bold py-1 px-3 rounded-lg text-[11px] transition-all">
                                Guardar Cambios de Acceso
                            </button>
                        </div>
                        
                        <div class="flex flex-wrap gap-x-6 gap-y-2">
                            <label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="ver_inventario" {{ in_array('ver_inventario', $userPerms) ? 'checked' : '' }} class="rounded border-slate-300 text-[#051c11] focus:ring-[#051c11]/20 w-4 h-4 transition-all"> Ver Inventario
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="editar_inventario" {{ in_array('editar_inventario', $userPerms) ? 'checked' : '' }} class="rounded border-slate-300 text-[#051c11] focus:ring-[#051c11]/20 w-4 h-4 transition-all"> Editar Inventario
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="gestionar_compras" {{ in_array('gestionar_compras', $userPerms) ? 'checked' : '' }} class="rounded border-slate-300 text-[#051c11] focus:ring-[#051c11]/20 w-4 h-4 transition-all"> Gestionar Compras
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="ver_reportes" {{ in_array('ver_reportes', $userPerms) ? 'checked' : '' }} class="rounded border-slate-300 text-[#051c11] focus:ring-[#051c11]/20 w-4 h-4 transition-all"> Ver Reportes
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="procesar_ventas" {{ in_array('procesar_ventas', $userPerms) ? 'checked' : '' }} class="rounded border-slate-300 text-[#051c11] focus:ring-[#051c11]/20 w-4 h-4 transition-all"> Procesar Ventas
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700 font-medium cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="gestionar_usuarios" {{ in_array('gestionar_usuarios', $userPerms) ? 'checked' : '' }} class="rounded border-slate-300 text-[#051c11] focus:ring-[#051c11]/20 w-4 h-4 transition-all"> Gestionar Usuarios
                            </label>
                        </div>
                    </div>
                </form>

            </div>
            @endforeach
        </div>
    </div>

    <div x-show="tab === 'seguridad'" x-transition style="display: none;">
        <div class="max-w-2xl bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <div class="flex items-start gap-4 mb-6">
                <div class="p-3 bg-slate-50 text-slate-500 rounded-xl border border-slate-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 022 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Actualizar Contraseña</h3>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Asegura tu cuenta con una contraseña robusta.</p>
                </div>
            </div>

            <form action="/usuarios/actualizar-password" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Contraseña Actual</label>
                    <input type="password" name="current_password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all text-slate-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all text-slate-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all text-slate-800">
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-[#051c11] hover:bg-[#082a1a] text-white font-bold py-3 px-6 rounded-xl text-sm transition-all shadow-sm">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition style="display: none;">
        
        <div class="bg-white w-full max-w-[480px] rounded-[1.5rem] p-6 shadow-2xl relative" @click.away="openModal = false">
            
            <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="mb-6">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Registrar Nuevo Usuario</h3>
            </div>

            <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nombre Completo</label>
                    <input type="text" name="name" required placeholder="Ej. Juan Pérez" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Correo Electrónico Corporativo</label>
                    <input type="email" name="email" required placeholder="jperez@corporacionportugal.com" class="w-full bg-slate-50/60 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Rol Principal</label>
                    <select name="role_id" required class="w-full bg-slate-50/60 border border-slate-200 text-slate-800 text-sm font-medium rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#051c11]/10 focus:border-[#051c11] transition-all">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nombre_rol }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openModal = false" class="border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-[#051c11] hover:bg-[#0c2a1c] text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all shadow-sm">
                        Guardar Usuario
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection