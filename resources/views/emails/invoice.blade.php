<!DOCTYPE html>
<html>
<head>
    <title>Factura</title>
</head>
<body>
    <h1>Factura emitida</h1>

    <p>Hola, {{ $customerName }}</p>

    <p>Gracias por tu pedido. Aquí tienes los detalles de tu factura:</p>

    <p><strong>Número de factura:</strong> {{ $numeroFactura }}</p>
    <p><strong>Fecha de emisión:</strong> {{ $fechaEmision }}</p>
    <p><strong>Empleado que emitió la factura:</strong> {{ $empleadoNombre }}</p>
    <p><strong>Farmacia:</strong> {{ $farmaciaNombre }}</p>

    <table border="1" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th>Medicamento</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceDetails as $detalle)
            <tr>
                <td>{{ $detalle->monodroga }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>${{ number_format($detalle->precio, 2) }}</td>
                <td>${{ number_format($detalle->total_por_medicamento, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total A Pagar : </strong>${{ number_format($totalInvoice, 2) }}</p>
</body>
</html>
