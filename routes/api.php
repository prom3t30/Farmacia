<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FarmaciaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\PedidoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'loguin']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('empleados')->middleware('auth:sanctum')->group(function() {
    Route::get('/', [EmpleadoController::class, 'index2']); // Obtener lista de empleados
    Route::get('{ci}', [EmpleadoController::class, 'show']); // Obtener detalles de un empleado por CI
    Route::post('/', [EmpleadoController::class, 'store']); // Crear un nuevo empleado
    Route::put('{ci}', [EmpleadoController::class, 'update']); // Actualizar un empleado
    Route::delete('{ci}', [EmpleadoController::class, 'destroy']); // Eliminar un empleado
});


Route::prefix('laboratorios')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [LaboratorioController::class, 'indexApi']); // Obtener todos los laboratorios
    Route::get('{id}', [LaboratorioController::class, 'show']); // Obtener un laboratorio por ID
    Route::post('/', [LaboratorioController::class, 'store']); // Crear un nuevo laboratorio
    Route::put('{id}', [LaboratorioController::class, 'update']); // Actualizar un laboratorio existente
    Route::delete('{id}', [LaboratorioController::class, 'destroy']); // Eliminar un laboratorio
});


Route::prefix('pedidos')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PedidoController::class, 'indexApi']); // Obtener todos los pedidos
    Route::get('{id}', [PedidoController::class, 'show']); // Obtener un pedido por ID
    Route::post('/', [PedidoController::class, 'store']); // Crear un nuevo pedido
    Route::put('{id}', [PedidoController::class, 'update']); // Actualizar un pedido existente
    Route::delete('{id}', [PedidoController::class, 'destroy']); // Eliminar un pedido
});

Route::prefix('farmacias')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [FarmaciaController::class, 'indexApi']); // Obtener todas las farmacias
    Route::get('{id}', [FarmaciaController::class, 'show']); // Obtener una farmacia por ID
    Route::post('/', [FarmaciaController::class, 'store']); // Crear una nueva farmacia
    Route::put('{id}', [FarmaciaController::class, 'update']); // Actualizar una farmacia
    Route::delete('{id}', [FarmaciaController::class, 'destroy']); // Eliminar una farmacia
});


Route::prefix('medicamentos')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MedicamentoController::class, 'indexApi']); // Obtener todos los medicamentos
    Route::get('{id}', [MedicamentoController::class, 'show']); // Obtener un medicamento por ID
    Route::post('/', [MedicamentoController::class, 'store']); // Crear un nuevo medicamento
    Route::put('{id}', [MedicamentoController::class, 'update']); // Actualizar un medicamento
    Route::delete('{id}', [MedicamentoController::class, 'destroy']); // Eliminar un medicamento
});



Route::prefix('inventarios')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [InventarioController::class, 'indexApi']); // Obtener todos los inventarios
    Route::get('{id}', [InventarioController::class, 'show']); // Obtener un inventario por ID
    Route::post('/', [InventarioController::class, 'store']); // Crear un nuevo registro
    Route::put('{id}', [InventarioController::class, 'update']); // Actualizar un registro
    Route::delete('{id}', [InventarioController::class, 'destroy']); // Eliminar un registro
});


Route::prefix('compras')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CompraController::class, 'indexApi']); // Obtener todas las compras
    Route::get('{id}', [CompraController::class, 'show']); // Obtener una compra específica por ID
    Route::post('/', [CompraController::class, 'store']); // Crear una nueva compra
    Route::put('{id}', [CompraController::class, 'update']); // Actualizar una compra existente
    Route::delete('{id}', [CompraController::class, 'destroy']); // Eliminar una compra
});


Route::prefix('facturas')->middleware('auth:sanctum')->group(function () {
    Route::post('/emitir/{pedidoId}', [FacturaController::class, 'emitirFactura']); // Emitir una factura
    Route::get('/{id}', [FacturaController::class, 'obtenerFactura']); // Obtener una factura por ID
});
