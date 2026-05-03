<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Padre\DashboardController;
use App\Http\Controllers\Padre\HijoController;
use App\Http\Controllers\Padre\TareaController;
use App\Http\Controllers\Padre\RecompensaController;
use App\Http\Controllers\Padre\ValidacionController;
use App\Http\Controllers\Padre\CanjeController;
use App\Http\Controllers\Padre\PerfilController;
use App\Http\Controllers\Padre\JuegoController as PadreJuegoController;
use App\Http\Controllers\Padre\MonedaController;
use App\Http\Controllers\Padre\EstadisticasController;
use App\Http\Controllers\Padre\SolicitudPinController;
use App\Http\Controllers\Hijo\SesionController;
use App\Http\Controllers\Hijo\DashboardController as HijoDashboardController;
use App\Http\Controllers\Hijo\JuegoController as HijoJuegoController;
use App\Http\Controllers\Hijo\PerfilController as HijoPerfilController;
use App\Http\Controllers\Hijo\SolicitudPinController as HijoSolicitudPinController;
use Illuminate\Support\Facades\Route;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Páginas legales
Route::get('/privacidad', fn() => view('legal.privacidad'))->name('privacidad');
Route::get('/terminos',   fn() => view('legal.terminos'))->name('terminos');

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
    Route::post('/perfil/avatar', [PerfilController::class, 'updateAvatar'])->name('perfil.avatar');
    Route::delete('/perfil/avatar', [PerfilController::class, 'eliminarAvatar'])->name('perfil.avatar.eliminar');
    Route::delete('/perfil/cuenta', [PerfilController::class, 'eliminarCuenta'])->name('perfil.cuenta.eliminar');

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

    // Configuración de juegos educativos
    Route::get('/juegos', [PadreJuegoController::class, 'index'])->name('juegos.index');
    Route::post('/juegos', [PadreJuegoController::class, 'guardar'])->name('juegos.guardar');

    // Ajuste manual de monedas
    Route::post('/hijos/{hijo}/monedas/ajustar', [MonedaController::class, 'ajustar'])->name('hijos.monedas.ajustar');

    // Estadísticas
    Route::get('/estadisticas', [EstadisticasController::class, 'index'])->name('estadisticas');

    // Solicitudes de cambio de PIN
    Route::get('/solicitudes-pin', [SolicitudPinController::class, 'index'])->name('solicitudes_pin.index');
    Route::post('/solicitudes-pin/{solicitud}/aprobar', [SolicitudPinController::class, 'aprobar'])->name('solicitudes_pin.aprobar');
    Route::post('/solicitudes-pin/{solicitud}/rechazar', [SolicitudPinController::class, 'rechazar'])->name('solicitudes_pin.rechazar');
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

    // Perfil del hijo
    Route::post('/perfil/avatar', [HijoPerfilController::class, 'updateAvatar'])->name('perfil.avatar');
    Route::delete('/perfil/avatar', [HijoPerfilController::class, 'eliminarAvatar'])->name('perfil.avatar.eliminar');

    // Juegos educativos
    Route::get('/juegos', [HijoJuegoController::class, 'index'])->name('juegos');
    Route::get('/juegos/{juego}', [HijoJuegoController::class, 'jugar'])->name('juegos.jugar');
    Route::post('/juegos/{juego}/completar', [HijoJuegoController::class, 'completar'])->name('juegos.completar');

    // Solicitud de cambio de PIN
    Route::post('/perfil/solicitar-pin', [HijoSolicitudPinController::class, 'solicitar'])->name('perfil.solicitar_pin');
});
