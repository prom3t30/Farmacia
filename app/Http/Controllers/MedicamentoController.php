<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicamento;

class MedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicamentos = Medicamento::all();
        return view('medicamento.index')->with('medicamentos',$medicamentos);
    }

    /**
     * Obtener todos los medicamentos (API).
     */
    public function indexApi()
    {
        $medicamentos = Medicamento::all();
        return response()->json($medicamentos, 200);
    }

    /**
     * Obtener un medicamento específico (API).
     */
    public function show($id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json(['error' => 'Medicamento no encontrado'], 404);
        }

        return response()->json($medicamento, 200);
    }

    /**
     * Crear un nuevo medicamento (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'monodroga' => 'required|string|max:255',
            'presentacion' => 'required|string|max:255',
            'accion' => 'required|string|max:255',
            'precio' => 'required|integer|min:0',
        ]);

        $medicamento = Medicamento::create($validated);

        return response()->json($medicamento, 201);
    }

    /**
     * Actualizar un medicamento existente (API).
     */
    public function update(Request $request, $id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json(['error' => 'Medicamento no encontrado'], 404);
        }

        $validated = $request->validate([
            'monodroga' => 'string|max:255',
            'presentacion' => 'string|max:255',
            'accion' => 'string|max:255',
            'precio' => 'integer|min:0',
        ]);

        $medicamento->update($validated);

        return response()->json($medicamento, 200);
    }

    /**
     * Eliminar un medicamento (API).
     */
    public function destroy($id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json(['error' => 'Medicamento no encontrado'], 404);
        }

        $medicamento->delete();

        return response()->json(['message' => 'Medicamento eliminado'], 200);
    }
}
