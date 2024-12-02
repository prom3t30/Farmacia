<?php

namespace App\Http\Controllers;
use App\Models\Farmacia;
use Illuminate\Http\Request;

class FarmaciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('farmacia.index');
    }

     /**
     * Obtener todas las farmacias (API).
     */
    public function indexApi()
    {
        $farmacias = Farmacia::with(['empleados', 'inventarios', 'pedidos'])->get();
        return response()->json($farmacias, 200);
    }

    /**
     * Obtener una farmacia específica (API).
     */
    public function show($id)
    {
        $farmacia = Farmacia::with(['empleados', 'inventarios', 'pedidos'])->find($id);

        if (!$farmacia) {
            return response()->json(['error' => 'Farmacia no encontrada'], 404);
        }

        return response()->json($farmacia, 200);
    }

    /**
     * Crear una nueva farmacia (API).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:15',
        ]);

        $farmacia = Farmacia::create($validated);

        return response()->json($farmacia, 201);
    }

    /**
     * Actualizar una farmacia existente (API).
     */
    public function update(Request $request, $id)
    {
        $farmacia = Farmacia::find($id);

        if (!$farmacia) {
            return response()->json(['error' => 'Farmacia no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'ubicacion' => 'string|max:255',
            'telefono' => 'nullable|string|max:15',
        ]);

        $farmacia->update($validated);

        return response()->json($farmacia, 200);
    }

    /**
     * Eliminar una farmacia (API).
     */
    public function destroy($id)
    {
        $farmacia = Farmacia::find($id);

        if (!$farmacia) {
            return response()->json(['error' => 'Farmacia no encontrada'], 404);
        }

        $farmacia->delete();

        return response()->json(['message' => 'Farmacia eliminada'], 200);
    }
}
