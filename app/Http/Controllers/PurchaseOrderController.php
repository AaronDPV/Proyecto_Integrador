<?php

namespace App\Http\Controllers;

use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = OrdenCompra::with('proveedor', 'detalles.producto')->get();
        $proveedores = Proveedor::all();
        $productos = Producto::all();

        return view('compras', compact('orders', 'proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_orden' => 'required|string|max:50|unique:orden_compras,numero_orden',
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_emision' => 'required|date',
            'estado' => 'required|string|in:Borrador,Enviado,Recibido',
            'notas' => 'nullable|string',
            'productos' => 'required|array|min:1',
        ]);

        $total_compra = 0;
        foreach ($request->productos as $item) {
            $total_compra += $item['cantidad'] * $item['precio_unitario'];
        }

        $order = OrdenCompra::create([
            'numero_orden' => $request->numero_orden,
            'proveedor_id' => $request->proveedor_id,
            'fecha_emision' => $request->fecha_emision,
            'estado' => $request->estado,
            'total_compra' => $total_compra,
            'notas' => $request->notas
        ]);

        foreach ($request->productos as $item) {
            DetalleCompra::create([
                'orden_compra_id' => $order->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario']
            ]);

            if ($request->estado === 'Recibido') {
                $prod = Producto::find($item['producto_id']);
                $prod->increment('stock_actual', $item['cantidad']);
            }
        }

        return redirect()->route('compras.index')->with('success', 'Orden de compra registrada de manera correcta.');
    }

    public function update(Request $request, $id)
    {
        $order = OrdenCompra::findOrFail($id);

        $request->validate([
            'estado' => 'required|string|in:Borrador,Enviado,Recibido',
            'notas' => 'nullable|string'
        ]);

        if ($order->estado !== 'Recibido' && $request->estado === 'Recibido') {
            foreach ($order->detalles as $detalle) {
                $prod = Producto::find($detalle->producto_id);
                $prod->increment('stock_actual', $detalle->cantidad);
            }
        }

        $order->update([
            'estado' => $request->estado,
            'notas' => $request->notas
        ]);

        return redirect()->route('compras.index')->with('success', 'Estado de la orden actualizado.');
    }

    public function destroy($id)
    {
        $order = OrdenCompra::findOrFail($id);
        $order->delete();

        return redirect()->route('compras.index')->with('success', 'Orden de compra eliminada del sistema.');
    }

    public function exportarPdf($id)
    {
        $order = OrdenCompra::with('proveedor', 'detalles.producto')->findOrFail($id);
        $fecha = Carbon::parse($order->fecha_emision)->isoFormat('dddd, D [de] MMMM [de] YYYY');
        
        $pdf = Pdf::loadView('pdf.compras_reporte', compact('order', 'fecha'));
        return $pdf->download('Orden_' . $order->numero_orden . '.pdf');
    }

    public function storeProveedor(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11|unique:proveedores,ruc',
            'razon_social' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
        ], [
            'ruc.required' => 'El número de RUC es obligatorio.',
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.unique' => 'Este número de RUC ya está registrado.',
            'razon_social.required' => 'La razón social es obligatoria.',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('compras.index')->with('success', 'Proveedor registrado exitosamente.');
    }
}