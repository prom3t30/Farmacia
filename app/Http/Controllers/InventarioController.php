<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventario.index');
    }

    /**
     * Obtener todos los inventarios (API).
     */
    public function indexApi()
    {
        $inventarios = Inventario::with(['farmacia', 'medicamento'])->get();
        return response()->json($inventarios, 200);
    }

    /**
     * Obtener un inventario específico (API).
     */
    public function show($id)
    {
        $inventario = Inventario::with(['farmacia', 'medicamento'])->find($id);

        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }

        return response()->json($inventario, 200);
    }

    /**
     * Crear un nuevo registro de inventario (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_farmacia' => 'required|exists:farmacias,id',
            'id_medicamento' => 'required|exists:medicamentos,id',
            'cantidad' => 'required|integer|min:0',
        ]);

        $inventario = Inventario::create($validated);

        return response()->json($inventario, 201);
    }

    /**
     * Actualizar un registro de inventario existente (API).
     */
    public function update(Request $request, $id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }

        $validated = $request->validate([
            'id_farmacia' => 'exists:farmacias,id',
            'id_medicamento' => 'exists:medicamentos,id',
            'cantidad' => 'integer|min:0',
        ]);

        $inventario->update($validated);

        return response()->json($inventario, 200);
    }

    /**
     * Eliminar un registro de inventario (API).
     */
    public function destroy($id)
    {
        $inventario = Inventario::find($id);

        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }

        $inventario->delete();

        return response()->json(['message' => 'Inventario eliminado'], 200);
    }

}
