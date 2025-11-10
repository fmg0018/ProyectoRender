<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura {{ $factura->numero ?? $factura->numero_factura }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Factura {{ $factura->numero ?? $factura->numero_factura }}</h2>
        <div>Fecha: {{ $factura->fecha_emision?->format('d/m/Y') }}</div>
    </div>

    <div>
        <strong>Cliente:</strong> {{ $factura->cliente?->nombre }}<br>
        <strong>Email:</strong> {{ $factura->cliente?->email }}
    </div>

    <br>
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Impuesto</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factura->lineas as $l)
                <tr>
                    <td>{{ $l->descripcion }}</td>
                    <td>{{ $l->cantidad }}</td>
                    <td>{{ number_format($l->precio_unitario, 2, ',', '.') }} €</td>
                    <td>{{ number_format($l->impuesto, 2, ',', '.') }} €</td>
                    <td>{{ number_format($l->total_linea, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <div style="text-align:right;">
        <div>Subtotal: {{ number_format($factura->subtotal,2,',','.') }} €</div>
        <div>Impuestos: {{ number_format($factura->impuestos,2,',','.') }} €</div>
        <div><strong>Total: {{ number_format($factura->total,2,',','.') }} €</strong></div>
    </div>
</body>
</html>
