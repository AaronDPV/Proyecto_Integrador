<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Pages\Auth\CustomLogin;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->favicon(asset('img/logo.png'))
            ->brandName('Corporación Portugal')
            ->login(CustomLogin::class)
            ->brandLogo(null) 
            ->darkMode(false)
            ->colors([
                'primary' => [
                    50 => '#f0fdf4',
                    100 => '#dcfce7',
                    200 => '#bbf7d0',
                    300 => '#86efac',
                    400 => '#4ade80',
                    500 => '#062315', 
                    600 => '#16a34a',
                    700 => '#15803d',
                    800 => '#166534',
                    900 => '#14532d',
                    950 => '#020d08',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            //->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Auth\Middleware\Authenticate::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::sidebar.nav.start',
            fn (): string => '
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1.5rem 1.25rem; background-color: #062315; border-bottom: 1px solid rgba(255,255,255,0.06); margin-bottom: 1rem;">
                    <div style="width: 2.5rem; height: 2.5rem; min-width: 2.5rem; background-color: #ffffff; border-radius: 9999px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 0.15rem; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <img src="' . asset('img/logo.png') . '" alt="Corporación Portugal" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #ffffff; letter-spacing: -0.025em; white-space: nowrap;">
                        ERP Portugal
                    </span>
                </div>
            '
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::sidebar.nav.end',
            fn (): string => '
                <div style="margin-top: auto; padding: 1.25rem 1rem; border-top: 1px solid rgba(255,255,255,0.06); background-color: #062315;">
                    <form action="' . route('filament.admin.auth.logout') . '" method="post" style="margin: 0; width: 100%;">
                        ' . csrf_field() . '
                        <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 0.65rem; padding: 0.75rem 1rem; background: none; border: none; border-radius: 0.5rem; color: rgba(255,255,255,0.75); font-size: 0.85rem; font-weight: 600; cursor: pointer; text-align: left; transition: all 0.2s;" onmouseover="this.style.backgroundColor=\'rgba(255,255,255,0.08)\'; this.style.color=\'#ffffff\'" onmouseout="this.style.backgroundColor=\'transparent\'; this.style.color=\'rgba(255,255,255,0.75)\'">
                            <svg style="width: 1.2rem; height: 1.2rem; opacity: 0.85;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </div>
            '
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::user-menu.before',
            fn (): string => '
                <div style="display: flex; flex-direction: column; text-align: right; margin-right: 0.75rem; user-select: none;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: #1f2937;">
                        ' . (auth()->user()->name ?? 'Administrador') . '
                    </span>
                    <span style="font-size: 0.7rem; font-weight: 500; color: #9ca3af; margin-top: -1px;">
                        Administrador
                    </span>
                </div>
            '
        );

        \Filament\Support\Facades\FilamentView::registerRenderHook(
            'panels::styles.after',
            fn (): string => '
                <style>
                    :root {
                        --sidebar-width: 16rem !important;
                    }

                    .fi-logo,
                    [class*="fi-logo"],
                    .fi-topbar-brand,
                    [class*="fi-topbar-brand"],
                    .fi-sidebar-header,
                    .fi-breadcrumbs,
                    [class*="fi-breadcrumbs"],
                    div[class*="fi-topbar-header-title"] {
                        display: none !important;
                        visibility: hidden !important;
                        width: 0 !important;
                        height: 0 !important;
                        padding: 0 !important;
                        margin: 0 !important;
                        opacity: 0 !important;
                    }

                    aside, 
                    .fi-sidebar,
                    .fi-sidebar-nav {
                        background-color: #062315 !important;
                        border-right: none !important;
                    }

                    aside {
                        position: fixed !important;
                        top: 0 !important;
                        left: 0 !important;
                        height: 100vh !important;
                        width: 16rem !important;
                        z-index: 30 !important;
                        display: flex !important;
                        flex-direction: column !important;
                    }

                    .fi-sidebar-nav {
                        display: flex !important;
                        flex-direction: column !important;
                        flex-grow: 1 !important;
                        height: 100% !important;
                        padding-top: 0 !important;
                    }

                    fi-topbar {
                            margin-left: 16rem !important;
                            width: calc(100% - 16rem) !important;
                        }

                    .fi-main-ctn,
                    main {
                        padding-left: 9rem !important;
                    }

                    .fi-main-ctn > div,
                    .fi-main-ctn .max-w-7xl,
                    .fi-main-ctn main > div {
                        max-width: 100% !important;
                        width: 100% !important;
                        padding-left: 2rem !important;   /* Aire sutil a la izquierda */
                        padding-right: 2rem !important;  /* Aire sutil a la derecha */
                        box-sizing: border-box !important;
                    }

                    .fi-sidebar-item {
                        margin-bottom: 0.25rem !important;
                        padding: 0 0.75rem !important;
                        background-color: transparent !important; 
                    }

                    .fi-sidebar-item-button {
                        padding: 0.75rem 1rem !important;
                        border-radius: 0.75rem !important;
                        transition: all 0.2s ease-in-out !important;
                        background-color: transparent !important; 
                    }

                    .fi-sidebar-item-button span, 
                    .fi-sidebar-item-button svg {
                        color: rgba(255, 255, 255, 0.91) !important;
                        font-size: 0.9rem !important;
                        font-weight: 600 !important;
                    }

                    .fi-sidebar-item-button:hover:not(.fi-active) {
                        background-color: rgba(255, 255, 255, 0.08) !important;
                    }
                    
                    .fi-sidebar-item-button:hover:not(.fi-active) span,
                    .fi-sidebar-item-button:hover:not(.fi-active) svg {
                        color: #ffffff !important;
                    }

                    aside .fi-sidebar-item-button.fi-active {
                        background-color: #113f28 !important; 
                        box-shadow: none !important;
                    }

                    aside .fi-sidebar-item-button.fi-active span, 
                    aside .fi-sidebar-item-button.fi-active svg {
                        color: #ffffff !important;
                        font-weight: 700 !important;
                        opacity: 1 !important;
                    }

                    aside .fi-sidebar-item-active,
                    aside [class*="item-active"] {
                        background-color: transparent !important;
                    }

                    aside .fi-sidebar-item-button::before,
                    aside .fi-sidebar-item-button::after {
                        display: none !important;
                        content: "" !important;
                    }
                </style>
            '
        );
    }
}