<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;

class DashboardController extends Controller
{
    public function index()
    {
        $ventas = Pedido::whereNotIn('estado', ['cancelado'])->sum('total');

        $ventasMensuales = collect(range(1, 12))->map(function (int $mes) {
            return Pedido::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $mes)
                ->whereNotIn('estado', ['cancelado'])
                ->sum('total');
        });

        return view('admin.dashboard', [
            'totalProductos' => Producto::count(),
            'totalClientes' => Cliente::count(),
            'totalPedidos' => Pedido::count(),
            'ventas' => $ventas,
            'pedidosPendientes' => Pedido::whereIn('estado', ['pendiente', 'confirmado', 'en_preparacion'])->count(),
            'productosBajoStock' => Inventario::bajoStock()->count(),
            'ultimosPedidos' => Pedido::with(['cliente', 'user'])->latest()->take(6)->get(),
            'ventasMensuales' => $ventasMensuales,
        ]);
    }
}
