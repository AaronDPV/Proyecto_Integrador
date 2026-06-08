<div style="position: fixed; inset: 0; width: 100vw; height: 100vh; background-color: #062315; display: flex; align-items: center; justify-content: center; z-index: 99999; box-sizing: border-box; padding: 1rem;">
    
    <div style="background-color: #ffffff; border-radius: 1.25rem; padding: 2.5rem 2rem; width: 100%; max-width: 24rem; display: flex; flex-direction: column; align-items: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4); border: 1px solid rgba(0,0,0,0.05); box-sizing: border-box;">
        
        <div style="width: 5rem; height: 5rem; background-color: #ffffff; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #f3f4f6; overflow: hidden; padding: 0.25rem;">
            @if(file_exists(public_path('img/logo.png')))
                <img src="{{ asset('img/logo.png') }}" alt="Corporación Portugal" style="width: 100%; height: 100%; object-fit: contain;">
            @else
                <span style="color: #062315; font-weight: 900; font-size: 1.75rem; tracking-tight: -0.05em;">CP</span>
            @endif
        </div>

        <h2 style="color: #1f2937; font-size: 1.25rem; font-weight: 700; text-align: center; margin: 0; tracking-tight: -0.025em;">Corporación Portugal</h2>
        <p style="color: #9ca3af; font-size: 0.75rem; text-align: center; margin-top: 0.25rem; margin-bottom: 1.75rem;">Ingresa tus credenciales para continuar</p>

        <form wire:submit.prevent="authenticate" style="width: 100%; display: flex; flex-direction: column; gap: 1rem; margin: 0;">
            @csrf
            
            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em;">Correo Electrónico</label>
                <input type="email" wire:model="email" required 
                    style="width: 100%; padding: 0.65rem 1rem; font-size: 0.875rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; color: #1f2937; box-sizing: border-box; outline: none;"
                    placeholder="ejemplo@correo.com">
                @error('email')
                    <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.125rem; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.25rem;" x-data="{ show: false }">
                <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em;">Contraseña</label>
                
                <div style="position: relative; width: 100%;">
                    <input :type="show ? 'text' : 'password'" wire:model="password" required 
                        style="width: 100%; padding: 0.65rem 2.5rem 0.65rem 1rem; font-size: 0.875rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; color: #1f2937; box-sizing: border-box; outline: none;"
                        placeholder="••••••••">
                    
                    <button type="button" @click="show = !show" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg x-show="show" style="width: 1.15rem; height: 1.15rem;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="!show" style="width: 1.15rem; height: 1.15rem;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                @error('password')
                    <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.125rem; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; align-items: center; margin-top: 0.25rem; margin-bottom: 0.5rem;">
                <label style="display: flex; align-items: center; color: #6b7280; font-size: 0.75rem; cursor: pointer; user-select: none;">
                    <input type="checkbox" wire:model="remember" style="margin-right: 0.5rem; width: 0.95rem; height: 0.95rem; border-radius: 0.25rem; border: 1px solid #d1d5db;">
                    Recordar sesión
                </label>
            </div>

            <button type="submit" 
                style="width: 100%; background-color: #062315; color: #ffffff; font-weight: 600; padding: 0.85rem; border-radius: 0.75rem; border: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(6, 35, 21, 0.2);">
                <span wire:loading.remove wire:target="authenticate">
                    Ingresar al Sistema
                </span>
                <span wire:loading wire:target="authenticate">
                    Autenticando...
                </span>
            </button>
        </form>
    </div>
</div>