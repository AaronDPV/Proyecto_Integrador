<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra - {{ $order->numero_orden }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; font-size: 12px; margin: 0; }
        .header { background-color: #051c11; color: white; padding: 24px; border-radius: 8px; margin-bottom: 20px; }
        .header h3 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0 0; font-size: 11px; color: #a7f3d0; }
        .info-box { margin-bottom: 20px; padding: 14px; border: 1px solid #e2e8f0; border-radius: 8px; bg-color: #f8fafc; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { font-size: 10px; color: #94a3b8; uppercase: true; padding: 10px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc; }
        td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; text-align: center; }
        .total-row { font-weight: bold; font-size: 13px; color: #051c11; }
    </style>
</head>
<body>

    <div class="header">
        <h3>Orden de Compra: {{ $order->numero_orden }}</h3>
        <p>Fecha de Emisión: {{ $fecha }}</p>
    </div>

    <div class="info-box">
        <strong>Proveedor:</strong> {{ $order->proveedor->razon_social }}<br>
        <strong>RUC:</strong> {{ $order->proveedor->ruc }}<br>
        <strong>Estado actual:</strong> {{ $order->estado }}<br>
        @if($order->notas)
            <br><strong>Notas de Compra:</strong> {{ $order->notas }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->detalles as $detalle)
            <tr>
                <td style="color: #64748b;">{{ $detalle->producto->sku }}</td>
                <td style="font-weight: bold;">{{ $detalle->producto->nombre }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td style="font-weight: bold;">${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right; padding-right: 15px;">TOTAL DE COMPRA:</td>
                <td>${{ number_format($order->total_compra, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>