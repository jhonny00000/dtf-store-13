<?php

use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventarioController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Publico\CarritoController;
use App\Http\Controllers\Publico\CatalogoController;
use App\Http\Controllers\Publico\HomeController;
use App\Http\Controllers\Publico\PedidoController as PedidoPublicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{producto:slug}', [CatalogoController::class, 'show'])->name('catalogo.show');

Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::patch('/carrito/{variante}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/{variante}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::delete('/carrito', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.intentar');
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('registro');
    Route::post('/registro', [AuthController::class, 'register'])->name('registro.guardar');
    Route::get('/olvide-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/olvide-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/restablecer-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/restablecer-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [PedidoPublicoController::class, 'checkout'])->name('pedidos.checkout');
    Route::post('/checkout', [PedidoPublicoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos', [PedidoPublicoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/exitoso/{pedido}', [PedidoPublicoController::class, 'exitoso'])->name('pedidos.exitoso');
    Route::get('/pedidos/{pedido}', [PedidoPublicoController::class, 'show'])->name('pedidos.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('productos', ProductoController::class);
    Route::post('productos/{producto}/estado', [ProductoController::class, 'cambiarEstado'])->name('productos.estado');
    Route::resource('categorias', CategoriaController::class);
    Route::resource('clientes', ClienteController::class)->except('destroy');
    Route::resource('pedidos', PedidoController::class)->only(['index', 'show', 'edit', 'update']);

    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('inventario/movimientos', [InventarioController::class, 'movimientos'])->name('inventario.movimientos');
    Route::get('inventario/{variante}/editar', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::put('inventario/{variante}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::post('inventario/{variante}/movimiento', [InventarioController::class, 'registrarMovimiento'])->name('inventario.movimiento');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
});
