<?php

use Facade\FlareClient\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/terminos', function () {
    return view('terminos');
})->name('terminos');


//Rutas para el establecimiento.
Route::group([
    'middleware' => 'establecimiento'
], function () {

    //Rutas Especialidades
    Route::get ('/home/especialidades',            [App\Http\Controllers\EspecialidadesController::class, 'index'])->name('especialidades');
    Route::post('/home/especialidades/crear',      [App\Http\Controllers\EspecialidadesController::class, 'create'])->name('crear_especialidades');
    Route::post('/home/especialidades/editar',     [App\Http\Controllers\EspecialidadesController::class, 'edit'])->name('editar_especialidades');
    Route::post('/home/especialidades/eliminar',   [App\Http\Controllers\EspecialidadesController::class, 'destroy'])->name('eliminar_especialidades');

    //Rutas Especialidades
    Route::get ('/home/especialistas',             [App\Http\Controllers\EspecialistasController::class, 'index'])->name('especialistas');
    Route::get ('/home/especialistas/crear',       [App\Http\Controllers\EspecialistasController::class, 'create'])->name('crear_especialistas');
    Route::post('/home/especialistas/guardar',     [App\Http\Controllers\EspecialistasController::class, 'store'])->name('guardar_especialistas');
    Route::get('/home/especialistas/editar/{id}',  [App\Http\Controllers\EspecialistasController::class, 'edit']);
    Route::post('/home/especialistas/actualizar',  [App\Http\Controllers\EspecialistasController::class, 'update'])->name('editar_especialistas');
    Route::post('/home/especialistas/eliminar',    [App\Http\Controllers\EspecialistasController::class, 'destroy'])->name('eliminar_especialistas');

    //Rutas Horario
    Route::get  ('/home/horarios',                 [App\Http\Controllers\HorariosController::class, 'index'])->name('horarios');
    Route::post ('/home/horarios/crear',           [App\Http\Controllers\HorariosController::class, 'create'])->name('crear_horario');
    Route::post ('/home/horarios/eliminar',        [App\Http\Controllers\HorariosController::class, 'destroy'])->name('eliminar_horario');

    //Rutas Bloques
    Route::get  ('/home/horarios/bloque/{id}',     [App\Http\Controllers\BloquesController::class, 'index'])->name('bloques');
    Route::post ('/home/horarios/bloque/crear',    [App\Http\Controllers\BloquesController::class, 'create'])->name('crear_bloque');
    Route::post('/home/horarios/bloque/editar',    [App\Http\Controllers\BloquesController::class, 'update'])->name('editar_bloque');
    Route::post ('/home/horarios/bloque/eliminar', [App\Http\Controllers\BloquesController::class, 'destroy'])->name('eliminar_bloque');

    //Rutas Atenciones
    Route::get('/home/atenciones/establecimiento', [App\Http\Controllers\AtencionController::class, 'index'])->name('atenciones_establecimiento');

    //Ruta para configuraciones.
    Route::get('/home/configuracion/establecimiento',          [App\Http\Controllers\ConfiguracionesController::class, 'establecimiento'])->name('configuracion_establecimiento');
    Route::post('/home/configuracion/establecimiento/cambiar', [App\Http\Controllers\ConfiguracionesController::class, 'cambiarPassword'])->name('pass_establecimiento');
});

//Rutas para el especialista.
Route::group([
    'middleware' => 'especialista'
], function () {

    //Rutas atenciones
    Route::get('/home/atenciones/especialista',        [App\Http\Controllers\AtencionController::class, 'index'])->name('atenciones_especialistas');
    Route::post('/home/atenciones/contactar/paciente', [App\Http\Controllers\AtencionController::class, 'contactarPaciente'])->name('atencion_contactar');
    Route::post('/home/atenciones/reenviar/paciente',  [App\Http\Controllers\AtencionController::class, 'reenviarPaciente'])->name('atencion_reenviar');
    Route::post('/home/atenciones/estado/cambiar',     [App\Http\Controllers\AtencionController::class, 'cambiarEstado'])->name('atencion_estado');
    Route::post('/home/atenciones/cancelar',           [App\Http\Controllers\AtencionController::class, 'cancelarAtencion'])->name('atencion_cancelar');

    //Rutas calendario
    Route::get('/home/calendario',              [App\Http\Controllers\EventosController::class, 'index'])->name('calendar');

    //Rutas Zoom
    Route::get('/home/account/zoom',            [App\Http\Controllers\ZoomController::class, 'index'])->name('zoom');
    Route::post('/home/account/zoom/auth',      [App\Http\Controllers\ZoomController::class, 'auth'])->name('authZoom');
    Route::get('/home/account/zoom/user',       [App\Http\Controllers\ZoomController::class, 'successAuth'])->name('authSuccess');
    Route::get('/home/account/zoom/desvincular',[App\Http\Controllers\ZoomController::class, 'desvincular'])->name('desZoom');
    Route::get('/home/account/zoom/reautorizar',[App\Http\Controllers\ZoomController::class, 'reautorizar'])->name('reZoom');
    Route::get('/home/account/zoom/meet',       [App\Http\Controllers\ZoomController::class, 'meet'])->name('zoomMeet');

    //Ruta para configuraciones.
    Route::get('/home/configuracion/especialista',          [App\Http\Controllers\ConfiguracionesController::class, 'especialista'])->name('configuracion_especialista');
    Route::post('/home/configuracion/especialista/cambiar', [App\Http\Controllers\ConfiguracionesController::class, 'cambiarPassword'])->name('pass_especialista');

    //Ruta para pacientes.
    Route::get('/home/pacientes',     [App\Http\Controllers\FichasController::class, 'index'])->name('pacientes');
    Route::get('/home/paciente/{id}', [App\Http\Controllers\FichasController::class, 'store']);
    Route::post('/home/paciente/nota', [App\Http\Controllers\FichasController::class, 'update'])->name('editar_nota');
});

//Rutas para el paciente.
Route::group([
    'middleware' => 'paciente'
], function () {
    
    //Rutas de atenciones.
    Route::get('/home/atenciones/paciente',                            [App\Http\Controllers\AtencionController::class, 'index'])->name('atenciones_pacientes');
    Route::get('/home/atenciones/paciente/reservar',                   [App\Http\Controllers\AtencionController::class, 'create'])->name('atenciones_reservar');
    Route::post('/home/atenciones/paciente/crear/reserva',             [App\Http\Controllers\AtencionController::class, 'store'])->name('atenciones_crear');
    Route::get('/home/atenciones/paciente/reservar/especialista/{id}', [App\Http\Controllers\AtencionController::class, 'show']);
    Route::post('/home/atenciones/paciente/reserva/eliminar',          [App\Http\Controllers\AtencionController::class, 'destroy'])->name('atenciones_eliminar');
    Route::post('/home/atenciones/paciente/reserva/registrar',         [App\Http\Controllers\AtencionController::class, 'update'])->name('atenciones_registrar');

    //Ruta de pagos
    Route::get('/home/atenciones/pago/checkout/{id}',                  [App\Http\Controllers\PagosController::class, 'index']);
    Route::get('/home/atenciones/pago/checkout/success/pago/{id}',     [App\Http\Controllers\PagosController::class, 'successPago'])->name('response_pago');

    //Ruta de Pdf
    //Route::get('/home/imprimir/comprobante/{id}',                    [App\Http\Controllers\PdfController::class, 'index']);

    //Ruta para configuraciones.
    Route::get('/home/configuracion/paciente',                         [App\Http\Controllers\ConfiguracionesController::class, 'paciente'])->name('configuracion_paciente');
    Route::post('/home/configuracion/paciente/cambiar',                [App\Http\Controllers\ConfiguracionesController::class, 'cambiarPassword'])->name('pass_paciente');

    //Ruta para ficha medica.
    Route::get('/home/ficha/medica',                                   [App\Http\Controllers\FichasController::class, 'paciente'])->name('ficha_medica');
});