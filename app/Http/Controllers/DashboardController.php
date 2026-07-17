<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\OrdenVenta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Calcular los indicadores directamente desde tu base de datos
        $ventasTotales = OrdenVenta::sum('total');
        $ordenesProcesadas = OrdenVenta::count();
        $stockBajo = Producto::whereRaw('stock_actual <= stock_critico')->count();
        $unidadesVendidas = DetalleVenta::sum('cantidad');

        // 2. Agrupar las ventas de los últimos días para el gráfico
        $ventasDiarias = OrdenVenta::select(
                DB::raw('DAYNAME(fecha_venta) as dia'),
                DB::raw('SUM(total) as total')
            )
            ->where('fecha_venta', '>=', Carbon::now()->subDays(6))
            ->groupBy(DB::raw('DAYNAME(fecha_venta)'), 'fecha_venta')
            ->orderBy('fecha_venta', 'asc')
            ->get();

        $diasMapeados = [
            'Monday' => 'Lun', 'Tuesday' => 'Mar', 'Wednesday' => 'Mie',
            'Thursday' => 'Jue', 'Friday' => 'Vie', 'Saturday' => 'Sab', 'Sunday' => 'Dom'
        ];

        $labelsGrafico = [];
        $dataVentasGrafico = [];

        foreach ($ventasDiarias as $vd) {
            $labelsGrafico[] = $diasMapeados[$vd->dia] ?? $vd->dia;
            $dataVentasGrafico[] = (float) $vd->total;
        }

        if (empty($labelsGrafico)) {
            $labelsGrafico = ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
            $dataVentasGrafico = [0, 0, 0, 0, 0, 0];
        }

        // 3. ¡EL PASO CLAVE! Pasar TODAS las variables a la vista usando compact()
        return view('dashboard', compact(
            'ventasTotales', 
            'ordenesProcesadas', 
            'stockBajo', 
            'unidadesVendidas',
            'labelsGrafico',
            'dataVentasGrafico'
        ));
    }
}