<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // La librería estándar para renderizar el PDF por código

class ReporteInventarioController extends Controller
{
    /**
     * Toda la lógica y maquetación del reporte de inventario en un solo método
     */
    public function descargarReporte()
    {
        $productos = DB::table('productos')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('productos.*', 'categorias.nombre_categoria')
            ->get();

        if ($productos->isEmpty()) {
            $categoriasGrouped = collect([
                'MOTORES' => [
                    (object)['sku' => 'PRT-001', 'nombre' => 'Motor Eléctrico Trifásico', 'stock_critico' => 5, 'stock_actual' => 12, 'precio_venta' => 1500.00]
                ],
                'HIDRÁULICA' => [
                    (object)['sku' => 'PRT-002', 'nombre' => 'Bomba Hidráulica X2', 'stock_critico' => 5, 'stock_actual' => 3, 'precio_venta' => 850.00],
                    (object)['sku' => 'PRT-004', 'nombre' => 'Válvula de Presión', 'stock_critico' => 10, 'stock_actual' => 0, 'precio_venta' => 1200.00]
                ],
                'PIEZAS' => [
                    (object)['sku' => 'PRT-003', 'nombre' => 'Rodamiento de Bolas', 'stock_critico' => 20, 'stock_actual' => 45, 'precio_venta' => 45.00]
                ]
            ]);
            $totalSKUs = 5;
            $totalCategorias = 4;
        } else {
            $totalSKUs = $productos->count();
            $totalCategorias = $productos->pluck('nombre_categoria')->unique()->count();
            $categoriasGrouped = $productos->groupBy('nombre_categoria');
        }

        $htmlContenido = "
        <html>
        <head>
            <style>
                body { font-family: 'Arial', sans-serif; color: #1f2937; margin: 15px; }
                .header-title { font-size: 22px; font-weight: bold; color: #062418; margin-bottom: 2px; }
                .header-date { font-size: 12px; color: #4b5563; margin-bottom: 20px; }
                .category-box { border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; background-color: #f9fafb; overflow: hidden; }
                .category-header { background-color: #f3f4f6; padding: 10px 15px; font-size: 13px; font-weight: bold; color: #062418; border-bottom: 1px solid #e5e7eb; }
                table { w-full; border-collapse: collapse; background: white; }
                th { text-transform: uppercase; font-size: 11px; color: #9ca3af; font-weight: bold; padding: 10px 15px; border-bottom: 1px solid #e5e7eb; text-align: left; }
                td { padding: 12px 15px; font-size: 13px; border-bottom: 1px solid #f3f4f6; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .stock-danger { color: #dc2626; font-weight: bold; } /* Rojo para stock crítico o cero */
                .footer-summary { display: flex; justify-content: space-between; font-size: 12px; color: #4b5563; margin-top: 15px; font-weight: bold; }
            </style>
        </head>
        <body>

            <div class='header-title'>Reporte General de Inventario</div>
            <div class='header-date'>Día del Reporte: " . date('l, d \d\e F \d\e Y') . "</div>";

        foreach ($categoriasGrouped as $nombreCategoria => $items) {
            $htmlContenido .= "
            <div class='category-box'>
                <div class='category-header'>📦 CATEGORÍA: " . strtoupper($nombreCategoria) . "</div>
                <table style='width: 100%;'>
                    <thead>
                        <tr>
                            <th style='width: 15%;'>SKU</th>
                            <th style='width: 45%;'>Descripción del Producto</th>
                            <th style='width: 15%; text-align: center;'>Stock Mínimo</th>
                            <th style='width: 15%; text-align: center;'>Stock Actual</th>
                            <th style='width: 10%; text-align: right;'>Valor Unit.</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($items as $prod) {
                $isCritico = $prod->stock_actual <= $prod->stock_critico;
                $stockClass = $isCritico ? "class='stock-danger text-center'" : "class='text-center' style='font-weight: bold;'";

                $htmlContenido .= "
                        <tr>
                            <td style='color: #6b7280;'>{$prod->sku}</td>
                            <td style='font-weight: bold; color: #111827;'>{$prod->nombre}</td>
                            <td class='text-center' style='color: #6b7280;'>{$prod->stock_critico}</td>
                            <td {$stockClass}>{$prod->stock_actual}</td>
                            <td class='text-right'>\$" . number_format($prod->precio_venta, 2) . "</td>
                        </tr>";
            }

            $htmlContenido .= "
                    </tbody>
                </table>
            </div>";
        }

        $htmlContenido .= "
            <div class='footer-summary'>
                <span>Total de Categorías: {$totalCategorias} | Total SKUs: {$totalSKUs}</span>
            </div>
        </body>
        </html>";

        // 3. RENDERIZADO Y DESCARGA AUTOMÁTICA
        return Pdf::loadHTML($htmlContenido)
            ->setPaper('a4', 'portrait')
            ->download('reporte_inventario_portugal.pdf');
    }
}