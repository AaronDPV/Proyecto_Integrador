<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleta - {{ $order->numero_boleta }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; font-size: 11px; margin: 0; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .logo-title { font-size: 16px; font-weight: bold; color: #051c11; margin: 0; }
        .doc-box { border: 1px solid #e2e8f0; padding: 12px 18px; text-align: center; border-radius: 6px; background-color: #f8fafc; }
        .info-grid { width: 100%; margin-bottom: 20px; border: 1px solid #f1f5f9; padding: 10px; border-radius: 6px; }
        table.items-table { width: 100%; border-collapse: collapse; }
        th { font-size: 9px; color: #94a3b8; text-transform: uppercase; padding: 8px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc; }
        td { padding: 10px 8px; border-bottom: 1px solid #f1f5f9; text-align: center; }
        .total-row td { text-align: right; font-weight: bold; padding-top: 8px; border: none; font-size: 12px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <h3 class="logo-title">Corporación Portugal</h3>
                <span style="color: #64748b; font-size: 10px;">Soluciones Industriales S.A.C.</span><br>
                <span style="color: #94a3b8; font-size: 10px;">RUC: 20546789123</span>
            </td>
            <td style="width: 45%; text-align: right;">
                <div class="doc-box">
                    <strong style="font-size: 10px; text-transform: uppercase; color: #64748b;">Boleta Electrónica</strong><br>
                    <span style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $order->numero_boleta }}</span><br>
                    <span style="font-size: 9px; color: #94a3b8;">{{ $fecha }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-grid">
        <tr>
            <td style="text-align: left; width: 50%;">
                <strong style="color: #475569;">Cliente:</strong> Cliente Mostrador<br>
                <span style="color: #64748b;">Doc: Varios</span>
            </td>
            <td style="text-align: right;">
                <strong style="color: #475569;">Condición:</strong> Contado / Efectivo<br>
                <span style="color: #64748b;">Moneda: USD ($)</span>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;">Cant.</th>
                <th>Descripción</th>
                <th style="width: 20%;">P. Unitario</th>
                <th style="width: 20%;">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->detalles as $detalle)
            <tr>
                <td style="color: #64748b;">{{ $detalle->cantidad }}</td>
                <td style="font-weight: bold; color: #0f172a;">{{ $detalle->producto->nombre }}</td>
                <td style="color: #475569;">${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td style="font-weight: bold; color: #0f172a;">${{ number_format($detalle->subtotal, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Subtotal:</td>
                <td style="text-align: center;">${{ number_format($order->importe_base, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3">IGV (18%):</td>
                <td style="text-align: center;">${{ number_format($order->igv, 2) }}</td>
            </tr>
            <tr class="total-row" style="color: #051c11;">
                <td colspan="3">TOTAL NETO:</td>
                <td style="text-align: center; font-size: 14px; color: #10b981;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>