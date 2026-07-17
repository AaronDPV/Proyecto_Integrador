<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ERP Portugal')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}?v={{ time() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased flex min-h-screen">

    <aside class="w-64 bg-[#051c11] text-white flex flex-col justify-between shrink-0">
        <div>
            <div class="px-6 py-5 flex items-center gap-3 border-b border-emerald-950/30">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-md overflow-hidden shrink-0">
                    <img src="{{ asset('img/logo.png') }}?v={{ time() }}" alt="Logo ERP Portugal" class="w-full h-full object-cover">
                </div>
                <span class="font-bold text-lg tracking-tight">ERP Portugal</span>
            </div>

            <nav class="mt-6 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-[#0c2a1c] text-white font-medium' : 'text-emerald-100/70 hover:text-white hover:bg-[#0c2a1c]/50' }} rounded-xl transition-all">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->tienePermiso('gestionar_usuarios'))
                <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('usuarios.index') ? 'bg-[#0c2a1c] text-white font-medium' : 'text-emerald-100/70 hover:text-white hover:bg-[#0c2a1c]/50' }} rounded-xl transition-all">
                    <svg class="w-5 h-5 {{ request()->routeIs('usuarios.index') ? 'text-emerald-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Usuarios
                </a>
                @endif

                @if(auth()->user()->tienePermiso('ver_inventario'))
                <a href="{{ route('inventario.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('inventario.index') ? 'bg-[#0c2a1c] text-white font-medium' : 'text-emerald-100/70 hover:text-white hover:bg-[#0c2a1c]/50' }} rounded-xl transition-all">
                    <svg class="w-5 h-5 {{ request()->routeIs('inventario.index') ? 'text-emerald-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Inventario
                </a>
                @endif

                @if(auth()->user()->tienePermiso('gestionar_compras'))
                <a href="{{ route('compras.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('compras.index') ? 'bg-[#0c2a1c] text-white font-medium' : 'text-emerald-100/70 hover:text-white hover:bg-[#0c2a1c]/50' }} rounded-xl transition-all">
                    <svg class="w-5 h-5 {{ request()->routeIs('compras.index') ? 'text-emerald-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Compras
                </a>
                @endif

                @if(auth()->user()->tienePermiso('procesar_ventas'))
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('pos.index') ? 'bg-[#0c2a1c] text-white font-medium' : 'text-emerald-100/70 hover:text-white hover:bg-[#0c2a1c]/50' }} rounded-xl transition-all">
                    <svg class="w-5 h-5 {{ request()->routeIs('pos.index') ? 'text-emerald-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Punto de Venta
                </a>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-emerald-950/30">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-200 hover:text-white hover:bg-red-900/30 rounded-xl transition-all font-semibold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        
        <header class="bg-white border-b border-slate-100 h-16 px-8 flex items-center justify-between shrink-0">
            <div class="relative w-80">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Buscar en el sistema..." class="w-full bg-slate-50 pl-10 pr-4 py-2 rounded-xl text-sm border-none focus:ring-2 focus:ring-[#051c11]/10 placeholder-slate-400 focus:outline-none">
            </div>

            <div class="flex items-center gap-4">
                <button class="relative p-1.5 text-slate-400 hover:text-slate-600 rounded-lg transition-all">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                </button>

                <div class="h-8 w-px bg-slate-200"></div>

                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900">¡Bienvenido, {{ auth()->user()->name }}!</p>
                        <p class="text-xs text-slate-400 font-medium">{{ auth()->user()->role->nombre_rol ?? 'Usuario' }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm border border-slate-200">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>