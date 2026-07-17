<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Corporación Portugal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#051c11] min-h-screen flex items-center justify-center px-4 antialiased">

    <div class="w-full max-w-[440px] bg-white rounded-[2rem] p-8 md:p-10 shadow-2xl transition-all">
        
        <div class="flex justify-center mb-6">

            <img src="{{ asset('img/logo.png') }}" alt="Corporación Portugal" class="h-20 w-auto object-contain"> 
            
        </div>

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-950 tracking-tight">Corporación Portugal</h1>
            <p class="text-sm text-gray-400 mt-1">Ingresa tus credenciales para continuar</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 tracking-wide mb-2">Correo Electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus placeholder="admin@corporacionportugal.com"
                        class="block w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-[#051c11]/20 focus:ring-offset-0 transition-all text-gray-800" />
                </div>
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-xs font-semibold text-gray-700 tracking-wide mb-2">Contraseña</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" name="password" id="password" required placeholder="••••••••"
                        class="block w-full pl-11 pr-11 py-3 bg-white border border-gray-200 rounded-xl text-sm placeholder-gray-300 focus:outline-none focus:border-transparent focus:ring-2 focus:ring-[#051c11]/20 focus:ring-offset-0 transition-all text-gray-800" />
                    
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <!-- Ojo abierto -->
                        <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#051c11] hover:bg-[#082a1a] active:scale-[0.98] text-white font-semibold py-3.5 px-4 rounded-xl transition-all duration-200 shadow-md flex items-center justify-center gap-2 mt-4 text-sm">
                Ingresar al ERP
            </button>
        </form>

    </div>

</body>
</html>