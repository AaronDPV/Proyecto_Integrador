<x-filament-panels::page>
    <style>
        .fi-header {
            display: none !important;
        }
        .fi-ac-header-actions {
            display: none !important;
        }
    </style>

    <div style="display: flex; flex-direction: column; gap: 24px; font-family: system-ui, sans-serif;">
        
       <div style="display: flex; flex-direction: column; gap: 4px;">
            <h1 style="font-size: 28px; font-weight: 800; color: #062418; margin: 0; letter-spacing: -0.025em;">
                Seguridad y Roles (RBAC)
            </h1>
            <p style="margin: 0; font-size: 14px; color: #6b7280; font-weight: 500;">
                Gestión de accesos y configuración de cuenta
            </p>
        </div>

        <div style="display: flex; gap: 24px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px;">
            <button 
                wire:click="$set('currentTab', 'roles')"
                style="display: flex; align-items: center; gap: 8px; padding-bottom: 8px; font-size: 14px; font-weight: 600; background: none; border: none; border-bottom: 2px solid {{ $currentTab === 'roles' ? '#062418' : 'transparent' }}; color: {{ $currentTab === 'roles' ? '#062418' : '#9ca3af' }}; cursor: pointer; transition: all 0.2s;"
            >
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Gestión de Roles</span>
            </button>

            <button 
                wire:click="$set('currentTab', 'seguridad')"
                style="display: flex; align-items: center; gap: 8px; padding-bottom: 8px; font-size: 14px; font-weight: 600; background: none; border: none; border-bottom: 2px solid {{ $currentTab === 'seguridad' ? '#062418' : 'transparent' }}; color: {{ $currentTab === 'seguridad' ? '#062418' : '#9ca3af' }}; cursor: pointer; transition: all 0.2s;"
            >
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>Seguridad</span>
            </button>
        </div>

        @if($currentTab === 'roles')
            <div style="display: flex; flex-direction: column; gap: 16px;">
                
                <div style="display: flex; justify-content: flex-end;">
                    <x-filament-actions::modals />
                    <button 
                        wire:click="mountAction('create')" 
                        type="button" 
                        style="display: inline-flex; align-items: center; justify-content: center; padding: 10px 20px; background-color: #062418; color: white; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; border: none; border-radius: 9999px; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.backgroundColor='#03140e'"
                        onmouseout="this.style.backgroundColor='#062418'"
                    >
                        + Agregar Usuario
                    </button>
                </div>

                @foreach(\App\Models\User::all() as $user)
                    <div style="background-color: white; border: 1px solid #f3f4f6; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); display: flex; flex-direction: column; gap: 16px;">
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                            
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 48px; height: 48px; border-radius: 9999px; background-color: #062418; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0; line-height: 1.2;">{{ $user->name }}</h3>
                                    <p style="font-size: 14px; color: #9ca3af; margin: 4px 0 0 0;">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span style="font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Rol Principal:</span>
                                <select 
                                    wire:change="updateUserRole({{ $user->id }}, $event.target.value)"
                                    style="font-size: 12px; font-weight: 700; text-transform: uppercase; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #f9fafb; color: #1f2937; padding: 8px 16px; cursor: pointer; outline: none; transition: border 0.2s;"
                                >
                                    <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Administrador</option>
                                    <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Vendedor</option>
                                    <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Inventario</option>
                                    <option value="" {{ is_null($user->role_id) ? 'selected' : '' }}>Sin Rol</option>
                                </select>
                            </div>
                        </div>

                        <div style="margin-top: 8px; padding-top: 16px; border-top: 1px solid #f3f4f6;">
                            <h4 style="font-size: 12px; font-weight: 700; color: #111827; text-transform: uppercase; margin: 0 0 12px 0;">Permisos Granulares</h4>
                            
                            <div style="display: flex; flex-wrap: wrap; gap: 24px;">
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #374151; cursor: not-allowed; opacity: 0.8;">
                                    <input type="checkbox" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #062418;" {{ $user->role_id == 1 || $user->role_id == 2 || $user->role_id == 3 ? 'checked' : '' }} disabled>
                                    <span>Ver Inventario</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #374151; cursor: not-allowed; opacity: 0.8;">
                                    <input type="checkbox" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #062418;" {{ $user->role_id == 1 ? 'checked' : '' }} disabled>
                                    <span>Editar Inventario</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #374151; cursor: not-allowed; opacity: 0.8;">
                                    <input type="checkbox" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #062418;" {{ $user->role_id == 1 ? 'checked' : '' }} disabled>
                                    <span>Gestionar Proveedores</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #374151; cursor: not-allowed; opacity: 0.8;">
                                    <input type="checkbox" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #062418;" {{ $user->role_id == 1 ? 'checked' : '' }} disabled>
                                    <span>Ver Reportes</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: #374151; cursor: not-allowed; opacity: 0.8;">
                                    <input type="checkbox" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #d1d5db; accent-color: #062418;" {{ $user->role_id == 1 || $user->role_id == 2 ? 'checked' : '' }} disabled>
                                    <span>Procesar Ventas</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($currentTab === 'seguridad')
            <div style="max-w: 576px; max-width: 576px; background-color: white; border: 1px solid #f3f4f6; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); display: flex; flex-direction: column; gap: 24px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="padding: 12px; background-color: #f9fafb; border-radius: 12px; color: #374151; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0;">Actualizar Contraseña</h3>
                        <p style="font-size: 14px; color: #9ca3af; margin: 4px 0 0 0;">Asegura tu cuenta con una contraseña robusta.</p>
                    </div>
                </div>

                <form wire:submit.prevent="updatePassword" style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase;">Contraseña Actual</label>
                        <input type="password" wire:model="current_password" style="width: 100%; font-size: 14px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #f9fafb; color: #111827; padding: 10px 16px; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase;">Nueva Contraseña</label>
                        <input type="password" wire:model="new_password" style="width: 100%; font-size: 14px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #f9fafb; color: #111827; padding: 10px 16px; outline: none;">
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        <label style="font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase;">Confirmar Contraseña</label>
                        <input type="password" wire:model="new_password_confirmation" style="width: 100%; font-size: 14px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #f9fafb; color: #111827; padding: 10px 16px; outline: none;">
                    </div>

                    <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                        <button type="submit" style="padding: 10px 20px; background-color: #062418; color: white; font-size: 14px; font-weight: 600; border: none; border-radius: 12px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#041c13'" onmouseout="this.style.backgroundColor='#062418'">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-filament-panels::page>