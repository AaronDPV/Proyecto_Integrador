<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Support\Schemas\Components\Group;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\Facades\DB;

class ReportSchema
{
    /**
     * Genera la lógica de métricas clave (KPIs) en tiempo real
     */
    public static function getWidgetsData(): array
    {
        $totalVentas = DB::table('orden_ventas')->sum('total') ?? 0;

        $productosCriticos = DB::table('productos')
            ->whereRaw('stock_actual <= stock_critico')
            ->count();

        $comprasPendientes = DB::table('orden_compras')
            ->where('estado', 'Pendiente')
            ->count();

        return [
            'total_ventas' => '$' . number_format($totalVentas, 2),
            'stock_critico' => $productosCriticos,
            'compras_pendientes' => $comprasPendientes,
        ];
    }

    /**
     * Estructura los datos para un gráfico lineal de ventas mensuales
     */
    public static function getVentasMensualesChartData(): array
    {
        $ventasPorMes = DB::table('orden_ventas')
            ->select(DB::raw('MONTH(fecha_venta) as mes'), DB::raw('SUM(total) as total'))
            ->whereYear('fecha_venta', 2026)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $data = array_fill(1, 12, 0);
        foreach ($ventasPorMes as $mes => $total) {
            $data[$mes] = (float) $total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas Realizadas ($)',
                    'data' => array_values($data),
                ],
            ],
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        ];
    }
}