<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        $products = Producto::with('categoria')->get();
        $categories = Category::all();

        $stockCriticoCount = $products->where('stock_actual', 0)->count();
        
        $reabastecimientoCount = $products->filter(function($product) {
            return $product->stock_actual > 0 && $product->stock_actual <= $product->stock_critico;
        })->count();

        $groupedProducts = $products->groupBy(function($product) {
            return $product->categoria->nombre_categoria ?? 'Sin Categoría';
        });

        return view('inventario', compact('products', 'categories', 'stockCriticoCount', 'reabastecimientoCount', 'groupedProducts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:50|unique:productos,sku',
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categories,id',
            'stock_actual' => 'required|integer|min:0',
            'stock_critico' => 'required|integer|min:0',
            'precio_view' => 'required|numeric|min:0',
        ], [
            'sku.required' => 'El código SKU es obligatorio.',
            'sku.unique' => 'Este código SKU ya se encuentra registrado en el almacén.',
            'sku.max' => 'El SKU no puede superar los 50 caracteres.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'Debes seleccionar una categoría válida.',
            'stock_actual.required' => 'El stock inicial es obligatorio.',
            'stock_actual.integer' => 'El stock debe ser un número entero.',
            'stock_actual.min' => 'El stock inicial no puede ser menor a 0.',
            'stock_critico.required' => 'El stock mínimo de aviso es obligatorio.',
            'stock_critico.min' => 'El stock mínimo no puede ser menor a 0.',
            'precio_view.required' => 'El precio base es obligatorio.',
            'precio_view.min' => 'El precio unitario no puede ser menor a 0.00.',
        ]);

        Producto::create($request->all());

        return redirect()->route('inventario.index')->with('success', '¡Producto añadido exitosamente al inventario!');
    }

    public function update(Request $request, $id)
    {
        $product = Producto::findOrFail($id);

        $request->validate([
            'sku' => 'required|string|max:50|unique:productos,sku,' . $id,
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categories,id',
            'stock_actual' => 'required|integer|min:0',
            'stock_critico' => 'required|integer|min:0',
            'precio_view' => 'required|numeric|min:0',
        ], [
            'sku.required' => 'El código SKU es obligatorio.',
            'sku.unique' => 'Este código SKU ya está asignado a otro producto.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'stock_actual.min' => 'El stock actual no puede ser menor a 0.',
            'stock_critico.min' => 'El stock mínimo no puede ser menor a 0.',
            'precio_view.min' => 'El precio unitario no puede ser menor a 0.00.',
        ]);

        $product->update($request->all());

        return redirect()->route('inventario.index')->with('success', '¡Producto actualizado exitosamente!');
    }

    public function destroy($id)
    {
        $product = Producto::findOrFail($id);
        $product->delete();

        return redirect()->route('inventario.index')->with('success', '¡Producto eliminado correctamente del sistema!');
    }

    public function exportarPdf()
    {
        $products = Producto::with('categoria')->get();
        
        $groupedProducts = $products->groupBy(function($product) {
            return $product->categoria->nombre_categoria ?? 'Sin Categoría';
        });

        $fecha = Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY');

        $pdf = Pdf::loadView('pdf.inventario_reporte', compact('products', 'groupedProducts', 'fecha'));
        
        return $pdf->download('Reporte_General_Inventario.pdf');
    }
}