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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Rutas Zoom
Route::get('/home/account/zoom', [App\Http\Controllers\ZoomController::class, 'index'])->name('zoom');
Route::post('/home/account/zoom/auth', [App\Http\Controllers\ZoomController::class, 'auth'])->name('authZoom');
Route::get('/home/account/zoom/user', [App\Http\Controllers\ZoomController::class, 'successAuth'])->name('authSuccess');
Route::get('/home/account/zoom/desvincular', [App\Http\Controllers\ZoomController::class, 'desvincular'])->name('desZoom');
Route::get('/home/account/zoom/reautorizar', [App\Http\Controllers\ZoomController::class, 'reautorizar'])->name('reZoom');
Route::get('/home/account/zoom/meet', [App\Http\Controllers\ZoomController::class, 'meet'])->name('zoomMeet');

//Rutas atenciones
Route::get('/home/calendario', [App\Http\Controllers\AtencionController::class, 'index'])->name('calendar');