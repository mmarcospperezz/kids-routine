<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Padre\DashboardController;
use App\Http\Controllers\Padre\HijoController;
use App\Http\Controllers\Padre\TareaController;
use App\Http\Controllers\Padre\RecompensaController;
use App\Http\Controllers\Padre\ValidacionController;
use App\Http\Controllers\Padre\CanjeController;
use App\Http\Controllers\Hijo\SesionController;
use App\Http\Controllers\Hijo\DashboardController as HijoDashboardController;
use Illuminate\Support\Facades\Route;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth de padres
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/registro', [RegisterController::class, 'show'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Panel del padre
Route::middleware('padre')->prefix('padre')->name('padre.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de hijos
    Route::get('/hijos', [HijoController::class, 'index'])->name('hijos.index');
    Route::get('/hijos/crear', [HijoController::class, 'create'])->name('hijos.create');
    Route::post('/hijos', [HijoController::class, 'store'])->name('hijos.store');
    Route::get('/hijos/{hijo}/editar', [HijoController::class, 'edit'])->name('hijos.edit');
    Route::put('/hijos/{hijo}', [HijoController::class, 'update'])->name('hijos.update');
    Route::delete('/hijos/{hijo}', [HijoController::class, 'destroy'])->name('hijos.destroy');

    // Gestión de tareas
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');

    // Gestión de recompensas
    Route::get('/recompensas', [RecompensaController::class, 'index'])->name('recompensas.index');
    Route::get('/recompensas/crear', [RecompensaController::class, 'create'])->name('recompensas.create');
    Route::post('/recompensas', [RecompensaController::class, 'store'])->name('recompensas.store');
    Route::delete('/recompensas/{recompensa}', [RecompensaController::class, 'destroy'])->name('recompensas.destroy');

    // Validación de tareas
    Route::get('/validaciones', [ValidacionController::class, 'index'])->name('validaciones');
    Route::post('/instancias/{instancia}/validar', [ValidacionController::class, 'validar'])->name('instancias.validar');
    Route::post('/instancias/{instancia}/rechazar', [ValidacionController::class, 'rechazar'])->name('instancias.rechazar');

    // Gestión de canjes
    Route::get('/canjes', [CanjeController::class, 'index'])->name('canjes.index');
    Route::post('/canjes/{canje}/aprobar', [CanjeController::class, 'aprobar'])->name('canjes.aprobar');
    Route::post('/canjes/{canje}/rechazar', [CanjeController::class, 'rechazar'])->name('canjes.rechazar');
    Route::post('/canjes/{canje}/entregar', [CanjeController::class, 'entregar'])->name('canjes.entregar');
});

// Sesión del hijo (necesita padre logueado)
Route::middleware('padre')->prefix('hijo')->name('hijo.')->group(function () {
    Route::get('/seleccionar', [SesionController::class, 'seleccionar'])->name('seleccionar');
    Route::post('/pin', [SesionController::class, 'mostrarPin'])->name('pin');
    Route::post('/verificar-pin', [SesionController::class, 'verificarPin'])->name('verificarPin');
    Route::post('/salir', [SesionController::class, 'salir'])->name('salir');
});

// Panel del hijo (necesita padre logueado + sesión hijo)
Route::middleware(['padre', 'hijo'])->prefix('hijo')->name('hijo.')->group(function () {
    Route::get('/dashboard', [HijoDashboardController::class, 'dashboard'])->name('dashboard');
    Route::post('/tareas/{instancia}/completar', [HijoDashboardController::class, 'completarTarea'])->name('tareas.completar');
    Route::get('/recompensas', [HijoDashboardController::class, 'recompensas'])->name('recompensas');
    Route::post('/recompensas/{recompensa}/canjear', [HijoDashboardController::class, 'canjear'])->name('recompensas.canjear');
});
