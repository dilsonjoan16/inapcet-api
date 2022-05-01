<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('usuarios/register', [UserController::class, 'register']);
Route::post('usuarios/login', [UserController::class, 'authenticate']);

// RUTA PARA CREAR EL USUARIO MAESTRO
Route::post('usuarios/master/register', [UserController::class, 'master_store']);

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/

    // RUTAS DE LOS USUARIO
    Route::post('usuarios/crear', [UserController::class, 'store']);
    Route::get('usuarios/activos', [UserController::class, 'index']);
    Route::get('usuarios/inactivos', [UserController::class, 'index_trashed']);
    Route::get('usuarios/ver/{id}', [UserController::class, 'show']);
    Route::put('usuarios/modificar/{id}', [UserController::class, 'update']);
    Route::put('usuarios/desactivar/{id}', [UserController::class, 'soft_delete']);
    Route::put('usuarios/activar/{id}', [UserController::class, 'restore']);
    Route::put('usuarios/activar/masivo', [UserController::class, 'restore_massive']);
    Route::delete('usuarios/eliminado/forzado/{id}', [UserController::class, 'force_delete']);
    Route::get('usuarios/logout', [UserController::class, 'logout']);

    // RUTAS DE LOS DOCUMENTOS
    Route::get('documentos/activos', [DocumentosController::class, 'index']);
    Route::get('documentos/inactivos', [DocumentosController::class, 'index_trashed']);
    Route::get('documentos/ver/{file}', [DocumentosController::class, 'show']);
    Route::post('documentos/crear', [DocumentosController::class, 'store']);
    Route::put('documentos/modificar/{id}', [DocumentosController::class, 'update']);
    Route::get('documentos/download/{file}', [DocumentosController::class, 'download']);
    Route::get('documentos/download/zip', [DocumentosController::class, 'download_zip']);
    Route::put('documentos/desactivar/{id}', [DocumentosController::class, 'soft_delete']);
    Route::put('documentos/activar/{id}', [DocumentosController::class, 'restore']);
    Route::put('documentos/activar/masivo', [DocumentosController::class, 'restore_massive']);
    Route::delete('documentos/eliminado/forzado/{id}', [DocumentosController::class, 'force_delete']);

    // RUTAS DE LOS ROLES
    Route::get('roles/activos', [RoleController::class, 'index']);
    Route::get('roles/inactivos', [RoleController::class, 'index_trashed']);
    Route::get('roles/ver/{id}', [RoleController::class, 'show']);
    Route::post('roles/crear', [RoleController::class, 'store']);
    Route::put('roles/modificar/{id}', [RoleController::class, 'update']);
    Route::put('roles/desactivar/{id}', [RoleController::class, 'soft_delete']);
    Route::put('roles/activar/{id}', [RoleController::class, 'restore']);
    Route::put('roles/activar/masivo', [RoleController::class, 'restore_massive']);
    Route::delete('roles/eliminado/forzardo/{id}', [RoleController::class, 'force_delete']);

    // RUTAS DE LOS DEPARTAMENTOS
    Route::get('departamentos/activos', [DepartamentoController::class, 'index']);
    Route::get('departamentos/inactivos', [DepartamentoController::class, 'index_trashed']);
    Route::get('departamentos/ver/{id}', [DepartamentoController::class, 'show']);
    Route::post('departamentos/crear', [DepartamentoController::class, 'store']);
    Route::put('departamentos/modificar/{id}', [DepartamentoController::class, 'update']);
    Route::put('departamentos/desactivar/{id}', [DepartamentoController::class, 'soft_delete']);
    Route::put('departamentos/activar/{id}', [DepartamentoController::class, 'restore']);
    Route::put('departamentos/activar/masivo', [DepartamentoController::class, 'restore_massive']);
    Route::delete('departamentos/eliminado/forzado/{id}', [DepartamentoControler::class, 'force_delete']);

    // RUTAS DE LOS PROYECTOS
    Route::get('proyectos/activos', [ProyectosController::class, 'index']);
    Route::get('proyectos/inactivos', [ProyectosController::class, 'index_trashed']);
    Route::get('proyectos/ver/{id}', [ProyectosController::class, 'show']);
    Route::post('proyectos/crear', [ProyectosController::class, 'store']);
    Route::put('proyectos/modificar/{id}/{state}', [ProyectosController::class, 'update']);
    Route::put('proyectos/desactivar/{id}', [ProyectosController::class, 'soft_delete']);
    Route::put('proyectos/activar/{id}', [ProyectosController::class, 'restore']);
    Route::put('proyectos/activar/masivo', [ProyectosController::class, 'restore_massive']);
    Route::delete('proyectos/eliminado/forzado/{id}', [ProyectosController::class, 'force_delete']);
});
