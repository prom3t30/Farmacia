<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pid = null)
    {
        $compras = Compra::all();
		return view('compra.index')
        ->with('compras',$compras)
        ->with('pid', $pid)
        ;
    }

    /**
     * Obtener todas las compras (API).
     */
    public function indexApi()
    {
        $compras = Compra::with(['farmacia', 'pedido', 'compraMedicamentos'])->get();
        return response()->json($compras, 200);
    }

    /**
     * Obtener una compra específica (API).
     */
    public function show($id)
    {
        $compra = Compra::with(['farmacia', 'pedido', 'compraMedicamentos'])->find($id);

        if (!$compra) {
            return response()->json(['error' => 'Compra no encontrada'], 404);
        }

        return response()->json($compra, 200);
    }

    /**
     * Crear una nueva compra (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_farmacia' => 'required|exists:farmacias,id',
            'id_pedido' => 'required|exists:pedidos,id',
            'vencimiento' => 'required|date',
            'cancelado' => 'required|boolean',
        ]);

        $compra = Compra::create($validated);

        return response()->json($compra, 201);
    }

    /**
     * Actualizar una compra existente (API).
     */
    public function update(Request $request, $id)
    {
        $compra = Compra::find($id);

        if (!$compra) {
            return response()->json(['error' => 'Compra no encontrada'], 404);
        }

        $validated = $request->validate([
            'id_farmacia' => 'exists:farmacias,id',
            'id_pedido' => 'exists:pedidos,id',
            'vencimiento' => 'date',
            'cancelado' => 'boolean',
        ]);

        $compra->update($validated);

        return response()->json($compra, 200);
    }

    /**
     * Eliminar una compra (API).
     */
    public function destroy($id)
    {
        $compra = Compra::find($id);

        if (!$compra) {
            return response()->json(['error' => 'Compra no encontrada'], 404);
        }

        $compra->delete();

        return response()->json(['message' => 'Compra eliminada'], 200);
    }
}
