<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceEmail;
use App\Models\Factura;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Agrega esta línea
use Illuminate\Support\Facades\Mail;
class FacturaController extends Controller
{
    /**
     * Crear y emitir una factura para un pedido.
     */
    /**
     * Crear y emitir una factura para un pedido.
     */
    public function emitirFactura(Request $request, $pedidoId)
{
    // Buscar el pedido
    $pedido = Pedido::find($pedidoId);

    if (!$pedido) {
        return response()->json(['error' => 'Pedido no encontrado'], 404);
    }

    // Obtener los detalles del pedido (medicamentos) y calcular el total
    $detalles = DB::table('pedido_medicamento')
        ->join('medicamentos', 'pedido_medicamento.id_medicamento', '=', 'medicamentos.id')
        ->where('pedido_medicamento.id_pedido', $pedidoId)
        ->select('pedido_medicamento.cantidad', 'medicamentos.precio', 'medicamentos.monodroga')
        ->get();

    if ($detalles->isEmpty()) {
        return response()->json(['error' => 'No se encontraron detalles para este pedido'], 404);
    }

    // Agrupar los detalles por 'monodroga' y sumar las cantidades
    $detallesAgrupados = $detalles->groupBy('monodroga')->map(function ($group) {
        $cantidadTotal = $group->sum('cantidad');
        $precio = $group->first()->precio;
        $totalPorMedicamento = $cantidadTotal * $precio;

        return (object)[
            'cantidad' => $cantidadTotal,
            'precio' => $precio,
            'monodroga' => $group->first()->monodroga,
            'total_por_medicamento' => $totalPorMedicamento
        ];
    });

    // Calcular el total del pedido
    $total = $detallesAgrupados->sum('total_por_medicamento');

    // Generar el número de factura único
    $numeroFactura = 'FAC-' . strtoupper(uniqid());

    // Crear la factura
    $factura = Factura::create([
        'id_pedido' => $pedido->id,
        'numero_factura' => $numeroFactura,
        'fecha_emision' => now(),
        'total' => $total,
    ]);

    // Incluir los detalles agrupados con el total por medicamento dentro de la factura
    $factura->detalles = $detallesAgrupados;

    // Obtener nombre del empleado que emitió la factura (asumimos que lo tienes en el modelo 'Empleado')
    $empleado = DB::table('empleados')->where('ci', $pedido->id_empleado)->first();
    $empleadoNombre = $empleado ? $empleado->nombre : 'Desconocido';

    // Obtener nombre de la farmacia (asumimos que lo tienes en el modelo 'Farmacia')
    $farmacia = DB::table('farmacias')->where('id', $pedido->id_farmacia)->first();
    $farmaciaNombre = $farmacia ? $farmacia->nombre : 'Desconocida';

    // Enviar el correo al cliente con la información adicional
    $customerEmail = 'andrus201232@gmail.com'; // Asegúrate de tener el email en el modelo Pedido
    $customerName = $farmaciaNombre; // Asegúrate de tener el nombre del cliente en el modelo Pedido
    $invoiceDetails = $detallesAgrupados->toArray();

    // Pasar todos los datos necesarios al correo
    Mail::to($customerEmail)->send(new InvoiceEmail($customerName, $invoiceDetails, $total, $numeroFactura, $factura->fecha_emision, $empleadoNombre, $farmaciaNombre));

    // Responder con la factura, los detalles dentro de la factura y el pedido
    return response()->json([
        'message' => 'Factura emitida con éxito',
        'factura' => $factura, // Incluye los detalles solo dentro de la factura
        'pedido' => $pedido,
    ], 201);
}





    /**
     * Obtener una factura específica.
     */
    public function obtenerFactura($id)
{
    // Buscar la factura por ID
    $factura = Factura::find($id);

    if (!$factura) {
        return response()->json(['error' => 'Factura no encontrada'], 404);
    }

    // Obtener los detalles del pedido (medicamentos) relacionados con esta factura
    $detalles = DB::table('pedido_medicamento')
        ->join('medicamentos', 'pedido_medicamento.id_medicamento', '=', 'medicamentos.id')
        ->where('pedido_medicamento.id_pedido', $factura->id_pedido)
        ->select('pedido_medicamento.cantidad', 'medicamentos.precio', 'medicamentos.monodroga')
        ->get();

    // Agrupar los detalles por 'monodroga' y sumar las cantidades
    $detallesAgrupados = $detalles->groupBy('monodroga')->map(function ($group) {
        $cantidadTotal = $group->sum('cantidad');
        $precio = $group->first()->precio;
        $totalPorMedicamento = $cantidadTotal * $precio;

        return (object)[
            'cantidad' => $cantidadTotal,
            'precio' => $precio,
            'monodroga' => $group->first()->monodroga,
            'total_por_medicamento' => $totalPorMedicamento
        ];
    });

    // Incluir los detalles dentro de la respuesta
    $factura->detalles = $detallesAgrupados;

    return response()->json($factura, 200);
}

}
