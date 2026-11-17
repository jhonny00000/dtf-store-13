<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $consulta = Pedido::with(['cliente', 'user']);

        if ($request->filled('estado')) {
            $consulta->where('estado', $request->string('estado')->toString());
        }

        if ($request->filled('desde')) {
            $consulta->whereDate('created_at', '>=', $request->date('desde'));
        }

        if ($request->filled('hasta')) {
            $consulta->whereDate('created_at', '<=', $request->date('hasta'));
        }

        return view('admin.pedidos.index', [
            'pedidos' => $consulta->latest()->paginate(15)->withQueryString(),
            'estados' => Pedido::$estados,
        ]);
    }

    public function show(Pedido $pedido)
    {
        return view('admin.pedidos.show', [
            'pedido' => $pedido->load(['cliente.user', 'user', 'detalles', 'pago']),
        ]);
    }

    public function edit(Pedido $pedido)
    {
        return view('admin.pedidos.edit', [
            'pedido' => $pedido->load('pago'),
            'estados' => Pedido::$estados,
        ]);
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $datos = $request->validate([
            'estado' => ['required', Rule::in(array_keys(Pedido::$estados))],
            'pago_estado' => ['required', Rule::in(['pendiente', 'pagado', 'rechazado'])],
            'referencia' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($datos, $pedido) {
            $estadoAnterior = $pedido->estado;

            if ($estadoAnterior === 'cancelado' && $datos['estado'] !== 'cancelado') {
                throw ValidationException::withMessages([
                    'estado' => 'Un pedido cancelado no puede reactivarse desde este módulo.',
                ]);
            }

            $pedido->update(['estado' => $datos['estado']]);

            if ($estadoAnterior !== 'cancelado' && $datos['estado'] === 'cancelado') {
                foreach ($pedido->detalles as $detalle) {
                    if (! $detalle->producto_variante_id) {
                        continue;
                    }

                    $inventario = Inventario::where('producto_variante_id', $detalle->producto_variante_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $inventario) {
                        continue;
                    }

                    $anterior = $inventario->stock;
                    $inventario->increment('stock', $detalle->cantidad);
                    MovimientoInventario::create([
                        'inventario_id' => $inventario->id,
                        'user_id' => auth()->id(),
                        'tipo' => 'entrada',
                        'cantidad' => $detalle->cantidad,
                        'stock_anterior' => $anterior,
                        'stock_nuevo' => $anterior + $detalle->cantidad,
                        'motivo' => 'Cancelación de pedido',
                        'referencia' => $pedido->numero,
                    ]);
                }
            }

            if ($pedido->pago) {
                $pedido->pago->update([
                    'estado' => $datos['pago_estado'],
                    'referencia' => $datos['referencia'] ?? null,
                    'fecha_pago' => $datos['pago_estado'] === 'pagado' ? now() : null,
                ]);
            }
        });

        return redirect()->route('admin.pedidos.show', $pedido)
            ->with('success', 'Pedido actualizado correctamente.');
    }
}
