<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Inventario</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .header {
            background-color: #051c11;
            color: white;
            padding: 24px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 11px;
            color: #a7f3d0;
        }
        .category-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .category-title {
            background-color: #f8fafc;
            padding: 10px 14px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            color: #051c11;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            font-size: 9px;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            padding: 10px 14px;
            border-b: 1px solid #e2e8f0;
            background-color: rgba(248, 250, 252, 0.5);
        }
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .sku-badge {
            color: #64748b;
            font-weight: bold;
        }
        .stock-critico { color: #dc2626; font-weight: bold; }
        .stock-alerta { color: #d97706; font-weight: bold; }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>Reporte General de Inventario</h3>
        <p>Día del Reporte: {{ $fecha }}</p>
    </div>

    @foreach($groupedProducts as $categoryName => $items)
    <div class="category-box">
        <div class="category-title">Categoría: {{ $categoryName }}</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%; text-align: center;">SKU</th>
                    <th style="text-align: center;">Descripción del Producto</th>
                    <th style="width: 18%; text-align: center;">Stock Mínimo</th>
                    <th style="width: 18%; text-align: center;">Stock Actual</th>
                    <th style="width: 18%; text-align: right;">Valor Unit.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td class="text-center sku-badge">{{ $item->sku }}</td>
                    <td class="text-center font-bold" style="color: #0f172a;">{{ $item->nombre }}</td>
                    <td class="text-center" style="color: #64748b;">{{ $item->stock_critico }}</td>
                    <td class="text-center">
                        <span class="{{ $item->stock_actual == 0 ? 'stock-critico' : ($item->stock_actual <= $item->stock_critico ? 'stock-alerta' : 'font-bold') }}">
                            {{ $item->stock_actual }}
                        </span>
                    </td>
                    <td class="text-right" style="color: #475569;">${{ number_format($item->precio_view, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        Total de Categorías: {{ $groupedProducts->count() }} | Total SKUs: {{ $products->count() }}
    </div>

</body>
</html>