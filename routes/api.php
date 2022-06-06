<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\MailController;
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

Route::post('usuarios/register', [UserController::class, 'register']);
Route::post('usuarios/login', [UserController::class, 'authenticate']);

// RUTA PARA CREAR EL USUARIO MAESTRO
// Route::post('usuarios/master/register', [UserController::class, 'master_store']);

        // FUNCION PARA CREAR EL USUARIO MAESTRO
Route::post('usuarios/register/professional', [UserController::class, 'register_pro']);

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/

    // RUTAS DE LOS USUARIO
    Route::post('usuarios/crear', [UserController::class, 'store']);
    Route::get('usuarios/activos', [UserController::class, 'index']);
    Route::get('usuarios/inactivos', [UserController::class, 'index_trashed']);
    Route::get('usuarios/ver/{id}', [UserController::class, 'show']);
    Route::put('usuarios/modificar/{id}', [UserController::class, 'update']);
    Route::get('usuarios/desactivar/{id}', [UserController::class, 'soft_delete']);
    Route::get('usuarios/activar/{id}', [UserController::class, 'restore']);
    Route::post('usuarios/activar/masivo', [UserController::class, 'restore_massive']);
    Route::post('usuarios/eliminado/forzado/{id}', [UserController::class, 'force_delete']);
    Route::get('usuarios/logout', [UserController::class, 'logout']);
    Route::get('usuarios/auditoria', [UserController::class, 'index_audit']);
    Route::get('usuarios/auditoria/individual/{id}', [UserController::class, 'show_audit']);

    // RUTAS DE LOS DOCUMENTOS
    Route::get('documentos/activos', [DocumentosController::class, 'index']);
    Route::get('documentos/activos/departamentos', [DocumentosController::class, 'index_departament']);
    Route::get('documentos/activos/usuarios', [DocumentosController::class, 'index_single']);
    Route::get('documentos/inactivos', [DocumentosController::class, 'index_trashed']);
    Route::get('documentos/ver/{id}', [DocumentosController::class, 'show']);
    Route::post('documentos/crear', [DocumentosController::class, 'store']);
    Route::post('documentos/modificar/{id}', [DocumentosController::class, 'update']);
    Route::get('documentos/download/{file}', [DocumentosController::class, 'download']);
    Route::get('documentos/download/zip', [DocumentosController::class, 'download_zip']);
    Route::get('documentos/desactivar/{id}', [DocumentosController::class, 'soft_delete']);
    Route::get('documentos/activar/{id}', [DocumentosController::class, 'restore']);
    Route::put('documentos/activar/masivo', [DocumentosController::class, 'restore_massive']);
    Route::get('documentos/activar/masivo/departamento', [DocumentosController::class, 'restore_massive_departament']);
    // Route::post('documentos/eliminado/forzado/{id}', [DocumentosController::class, 'force_delete']);
    Route::post('documentos/eliminado/permanente/{id}', [DocumentosController::class, 'force_delete']);
    Route::get('documentos/auditoria', [DocumentosController::class, 'index_audit']);

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
    Route::post('departamentos/modificar/{id}', [DepartamentoController::class, 'update']);
    Route::get('departamentos/desactivar/{id}', [DepartamentoController::class, 'soft_delete']);
    Route::get('departamentos/activar/{id}', [DepartamentoController::class, 'restore']);
    Route::put('departamentos/activar/masivo', [DepartamentoController::class, 'restore_massive']);
    Route::post('departamentos/eliminado/forzado/{id}', [DepartamentoControler::class, 'force_delete']);
    Route::get('departamentos/auditoria', [DepartamentoController::class, 'index_audit']);

    // RUTAS DE LOS PROYECTOS
    Route::get('proyectos/activos', [ProyectosController::class, 'index']);
    Route::get('proyectos/inactivos', [ProyectosController::class, 'index_trashed']);
    Route::get('proyectos/ver/{id}', [ProyectosController::class, 'show']);
    Route::post('proyectos/crear', [ProyectosController::class, 'store']);
    Route::post('proyectos/modificar/{id}/{state}', [ProyectosController::class, 'update']);
    Route::get('proyectos/desactivar/{id}', [ProyectosController::class, 'soft_delete']);
    Route::get('proyectos/activar/{id}', [ProyectosController::class, 'restore']);
    Route::put('proyectos/activar/masivo', [ProyectosController::class, 'restore_massive']);
    Route::post('proyectos/eliminado/forzado/{id}', [ProyectosController::class, 'force_delete']);
    Route::get('proyectos/auditoria', [ProyectosController::class, 'index_audit']);

    // RUTAS PARA LOS ENVIOS DE CORREOS ELECTRONICOS
    Route::post('recovery/password/user', [MailController:: class, 'passwordRecovery']);
    Route::post('recovery/code/user', [MailController::class , 'codeRecovery']);

});
