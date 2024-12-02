<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Pasantia;
use App\Models\Responsable;
use App\Models\Titulo;
use App\Models\Farmacia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('empleado.index');
	}

        // rutas  para los endpoinst
     // Listar todos los empleados
     public function index2()
     {
         $empleados = Empleado::all();
         return response()->json($empleados);
     }

     // Mostrar un empleado específico por CI
     public function show($ci)
     {
         $empleado = Empleado::find($ci);

         if (!$empleado) {
             return response()->json(['message' => 'Empleado no encontrado'], 404);
         }

         return response()->json($empleado);
     }

     // Crear un nuevo empleado
     public function store(Request $request)
     {
         $empleado = Empleado::create($request->all());
         return response()->json($empleado, 201);
     }

     // Actualizar un empleado existente
     public function update(Request $request, $ci)
     {
         $empleado = Empleado::find($ci);

         if (!$empleado) {
             return response()->json(['message' => 'Empleado no encontrado'], 404);
         }

         $empleado->update($request->all());
         return response()->json($empleado);
     }

     // Eliminar un empleado
     public function destroy($ci)
     {
         $empleado = Empleado::find($ci);

         if (!$empleado) {
             return response()->json(['message' => 'Empleado no encontrado'], 404);
         }

         $empleado->delete();
         return response()->json(['message' => 'Empleado eliminado'], 200);
     }
}
