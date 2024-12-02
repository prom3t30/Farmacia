<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Laboratorio;


class LaboratorioController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$laboratorios = Laboratorio::all();
		return view('laboratorio.index')->with('laboratorios',$laboratorios);
	}


     /**
     * Mostrar todos los laboratorios (para la API).
     */
    public function indexApi()
    {
        $laboratorios = Laboratorio::all();
        return response()->json($laboratorios, 200);
    }

    /**
     * Mostrar un laboratorio específico (API).
     */
    public function show($id)
    {
        $laboratorio = Laboratorio::find($id);

        if (!$laboratorio) {
            return response()->json(['error' => 'Laboratorio no encontrado'], 404);
        }

        return response()->json($laboratorio, 200);
    }

    /**
     * Crear un laboratorio (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
        ]);

        $laboratorio = Laboratorio::create($validated);

        return response()->json($laboratorio, 201);
    }

    /**
     * Actualizar un laboratorio existente (API).
     */
    public function update(Request $request, $id)
    {
        $laboratorio = Laboratorio::find($id);

        if (!$laboratorio) {
            return response()->json(['error' => 'Laboratorio no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'direccion' => 'string|max:255',
            'telefono' => 'string|max:15',
        ]);

        $laboratorio->update($validated);

        return response()->json($laboratorio, 200);
    }

    /**
     * Eliminar un laboratorio (API).
     */
    public function destroy($id)
    {
        $laboratorio = Laboratorio::find($id);

        if (!$laboratorio) {
            return response()->json(['error' => 'Laboratorio no encontrado'], 404);
        }

        $laboratorio->delete();

        return response()->json(['message' => 'Laboratorio eliminado'], 200);
    }
}
