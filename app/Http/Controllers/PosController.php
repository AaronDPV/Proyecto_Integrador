<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\OrdenVenta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index()
    {
        // Se cargan únicamente productos que tengan stock disponible
        $productos = Producto::where('stock_actual', '>', 0)->get();
        return view('pos', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            $orderId = DB::transaction(function () use ($request) {
                $importe_base = 0;

                foreach ($request->productos as $item) {
                    $producto = Producto::lockForUpdate()->find($item['id']);
                    
                    if ($producto->stock_actual < $item['cantidad']) {
                        throw new \Exception("El producto '{$producto->nombre}' no cuenta con stock suficiente (Disponible: {$producto->stock_actual}).");
                    }
                    
                    $importe_base += $item['cantidad'] * $producto->precio_view;
                }

                $igv = $importe_base * 0.18;
                $total = $importe_base + $igv;
                
                $ultimoId = OrdenVenta::max('id') ?? 0;
                $numero_boleta = 'B001-' . str_pad($ultimoId + 1, 6, '0', STR_PAD_LEFT);

                $venta = OrdenVenta::create([
                    'numero_boleta' => $numero_boleta,
                    'user_id' => Auth::id() ?? 1,
                    'fecha_venta' => Carbon::now(),
                    'importe_base' => $importe_base,
                    'igv' => $igv,
                    'total' => $total
                ]);

                foreach ($request->productos as $item) {
                    $producto = Producto::find($item['id']);
                    $subtotal = $item['cantidad'] * $producto->precio_view;

                    DetalleVenta::create([
                        'orden_venta_id' => $venta->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $producto->precio_view,
                        'subtotal' => $subtotal
                    ]);

                    $producto->decrement('stock_actual', $item['cantidad']);
                }

                return $venta->id;
            }); // <-- Aquí estaba el error (tenías ]); ahora es });

            $boletaCompleta = OrdenVenta::with('detalles.producto', 'user')->find($orderId);
            return response()->json([
                'success' => true,
                'boleta' => $boletaCompleta,
                'fecha_formateada' => Carbon::parse($boletaCompleta->fecha_venta)->isoFormat('DD [de] MMMM [de] YYYY [a las] hh:mm a')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getmessage()
            ], 422);
        }
    }
    
    public function exportarPdf($id)
    {
        $order = OrdenVenta::with('detalles.producto', 'user')->findOrFail($id);
        $fecha = Carbon::parse($order->fecha_venta)->isoFormat('DD [de] MMMM [de] YYYY [a las] hh:mm a');
        
        $pdf = Pdf::loadView('pdf.boleta_ticket', compact('order', 'fecha'));
        return $pdf->download("Boleta_{$order->numero_boleta}.pdf");
    }
}