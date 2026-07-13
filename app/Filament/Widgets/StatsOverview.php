<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\Users\Schemas\ReportSchema;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $metricas = ReportSchema::getWidgetsData();

        return [
            Stat::make('Ingresos Totales', $metricas['total_ventas'])
                ->description('Consolidado acumulado del POS')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Alertas de Inventario', $metricas['stock_critico'])
                ->description('Insumos en stock crítico')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($metricas['stock_critico'] > 0 ? 'danger' : 'success'),

            Stat::make('Órdenes de Compra', $metricas['compras_pendientes'])
                ->description('Por recibir de proveedores')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
        ];
    }
}