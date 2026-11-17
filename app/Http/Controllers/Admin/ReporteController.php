<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\Pedido;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->filled('desde') ? $request->date('desde')->startOfDay() : now()->subDays(30)->startOfDay();
        $hasta = $request->filled('hasta') ? $request->date('hasta')->endOfDay() : now()->endOfDay();

        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()->endOfDay()];
        }

        $pedidos = Pedido::whereBetween('created_at', [$desde, $hasta])
            ->whereNotIn('estado', ['cancelado'])
            ->get();

        $detalles = DetallePedido::whereHas('pedido', function ($consulta) use ($desde, $hasta) {
            $consulta->whereBetween('created_at', [$desde, $hasta])
                ->whereNotIn('estado', ['cancelado']);
        })->get();

        $productosMasVendidos = $detalles->groupBy('nombre_producto')
            ->map(fn ($grupo) => [
                'nombre' => $grupo->first()->nombre_producto,
                'cantidad' => $grupo->sum('cantidad'),
                'total' => $grupo->sum('subtotal'),
            ])
            ->sortByDesc('cantidad')
            ->take(10)
            ->values();

        $porEstado = collect(Pedido::$estados)->mapWithKeys(fn ($etiqueta, $clave) => [
            $etiqueta => Pedido::whereBetween('created_at', [$desde, $hasta])->where('estado', $clave)->count(),
        ]);

        $porDia = $pedidos->groupBy(fn (Pedido $pedido) => $pedido->created_at->format('Y-m-d'))
            ->map(fn ($grupo) => [
                'cantidad' => $grupo->count(),
                'total' => $grupo->sum('total'),
            ]);

        $inventarioBajo = Inventario::bajoStock()
            ->with(['variante.producto', 'variante.talla', 'variante.color'])
            ->get();

        return view('admin.reportes.index', [
            'desde' => $desde,
            'hasta' => $hasta,
            'totalVentas' => $pedidos->sum('total'),
            'cantidadPedidos' => $pedidos->count(),
            'ticketPromedio' => $pedidos->count() ? $pedidos->sum('total') / $pedidos->count() : 0,
            'unidadesVendidas' => $detalles->sum('cantidad'),
            'productosMasVendidos' => $productosMasVendidos,
            'porEstado' => $porEstado,
            'porDia' => $porDia,
            'inventarioBajo' => $inventarioBajo,
        ]);
    }
}
