<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Ruta de errores.
Route::get('/404', [App\Http\Controllers\HomeController::class, 'index'])->name('404');

//Rutas para el establecimiento.
Route::group([
    'middleware' => 'establecimiento'
], function () {

    //Rutas Especialidades
    Route::get ('/home/especialidades', [App\Http\Controllers\EspecialidadesController::class, 'index'])->name('especialidades');
    Route::post('/home/especialidades/crear', [App\Http\Controllers\EspecialidadesController::class, 'create'])->name('crear_especialidades');
    Route::post('/home/especialidades/editar', [App\Http\Controllers\EspecialidadesController::class, 'edit'])->name('editar_especialidades');
    Route::post ('/home/especialidades/eliminar', [App\Http\Controllers\EspecialidadesController::class, 'destroy'])->name('eliminar_especialidades');
    
});

//Rutas para el especialista.
Route::group([
    'middleware' => 'especialista'
], function () {

    //Rutas calendario
    Route::get('/home/calendario', [App\Http\Controllers\EventosController::class, 'index'])->name('calendar');

    //Rutas Zoom
    Route::get('/home/account/zoom', [App\Http\Controllers\ZoomController::class, 'index'])->name('zoom');
    Route::post('/home/account/zoom/auth', [App\Http\Controllers\ZoomController::class, 'auth'])->name('authZoom');
    Route::get('/home/account/zoom/user', [App\Http\Controllers\ZoomController::class, 'successAuth'])->name('authSuccess');
    Route::get('/home/account/zoom/desvincular', [App\Http\Controllers\ZoomController::class, 'desvincular'])->name('desZoom');
    Route::get('/home/account/zoom/reautorizar', [App\Http\Controllers\ZoomController::class, 'reautorizar'])->name('reZoom');
    Route::get('/home/account/zoom/meet', [App\Http\Controllers\ZoomController::class, 'meet'])->name('zoomMeet');

});

//Rutas para el paciente.
Route::group([
    'middleware' => 'paciente'
], function () {
    
});