<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('pedido.index')
		->with('pedidos', Pedido::all());
	}

     /**
     * Obtener todos los pedidos (API).
     */
    public function indexApi()
    {
        $pedidos = Pedido::with(['farmacia', 'laboratorio', 'empleado'])->get();
        return response()->json($pedidos, 200);
    }

    /**
     * Obtener un pedido específico (API).
     */
    public function show($id)
    {
        $pedido = Pedido::with(['farmacia', 'laboratorio', 'empleado'])->find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        return response()->json($pedido, 200);
    }

    /**
     * Crear un pedido (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_farmacia' => 'required|exists:farmacias,id',
            'id_laboratorio' => 'required|exists:laboratorios,id',
            'id_empleado' => 'required|exists:empleados,ci',
            'forma_pago' => 'required|string|max:50',
            'slug' => 'nullable|string|max:255',
        ]);

        $pedido = Pedido::create($validated);

        return response()->json($pedido, 201);
    }

    /**
     * Actualizar un pedido existente (API).
     */
    public function update(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $validated = $request->validate([
            'id_farmacia' => 'exists:farmacias,id',
            'id_laboratorio' => 'exists:laboratorios,id',
            'id_empleado' => 'exists:empleados,ci',
            'forma_pago' => 'string|max:50',
            'slug' => 'nullable|string|max:255',
        ]);

        $pedido->update($validated);

        return response()->json($pedido, 200);
    }

    /**
     * Eliminar un pedido (API).
     */
    public function destroy($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['error' => 'Pedido no encontrado'], 404);
        }

        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado'], 200);
    }

}
