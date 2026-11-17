<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\ProductoVariante;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $consulta = Inventario::with(['variante.producto', 'variante.talla', 'variante.color']);

        if ($request->filled('q')) {
            $busqueda = '%'.$request->string('q')->trim()->toString().'%';
            $consulta->whereHas('variante.producto', fn ($subconsulta) => $subconsulta->where('nombre', 'like', $busqueda));
        }

        if ($request->boolean('bajo')) {
            $consulta->bajoStock();
        }

        $inventarios = $consulta->get()->sortBy(function (Inventario $inventario) {
            return $inventario->variante?->producto?->nombre ?? '';
        });

        return view('admin.inventario.index', [
            'inventarios' => $inventarios,
            'bajoStock' => Inventario::bajoStock()->count(),
        ]);
    }

    public function movimientos(Request $request)
    {
        $consulta = MovimientoInventario::with(['inventario.variante.producto', 'user']);

        if ($request->filled('tipo')) {
            $consulta->where('tipo', $request->string('tipo')->toString());
        }

        return view('admin.inventario.movimientos', [
            'movimientos' => $consulta->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function edit(ProductoVariante $variante)
    {
        $variante->load(['producto', 'talla', 'color', 'inventario']);

        return view('admin.inventario.edit', compact('variante'));
    }

    public function update(Request $request, ProductoVariante $variante): RedirectResponse
    {
        $datos = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'precio_adicional' => ['required', 'numeric', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        DB::transaction(function () use ($datos, $variante) {
            $variante->update([
                'precio_adicional' => $datos['precio_adicional'],
                'activo' => $request->boolean('activo'),
            ]);

            $inventario = Inventario::where('producto_variante_id', $variante->id)->lockForUpdate()->firstOrFail();
            $anterior = $inventario->stock;
            $nuevo = (int) $datos['stock'];
            $diferencia = $nuevo - $anterior;

            $inventario->update([
                'stock' => $nuevo,
                'stock_minimo' => (int) $datos['stock_minimo'],
            ]);

            if ($diferencia !== 0) {
                MovimientoInventario::create([
                    'inventario_id' => $inventario->id,
                    'user_id' => auth()->id(),
                    'tipo' => 'ajuste',
                    'cantidad' => abs($diferencia),
                    'stock_anterior' => $anterior,
                    'stock_nuevo' => $nuevo,
                    'motivo' => 'Ajuste manual de inventario',
                    'referencia' => $variante->producto?->codigo ?? $variante->producto?->slug,
                ]);
            }
        });

        return redirect()->route('admin.inventario.index')
            ->with('success', 'Inventario actualizado correctamente.');
    }

    public function registrarMovimiento(Request $request, ProductoVariante $variante): RedirectResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', Rule::in(['entrada', 'salida'])],
            'cantidad' => ['required', 'integer', 'min:1'],
            'motivo' => ['nullable', 'string', 'max:200'],
        ]);

        DB::transaction(function () use ($datos, $variante) {
            $inventario = Inventario::where('producto_variante_id', $variante->id)->lockForUpdate()->firstOrFail();
            $anterior = $inventario->stock;
            $cantidad = (int) $datos['cantidad'];

            if ($datos['tipo'] === 'salida' && $anterior < $cantidad) {
                throw ValidationException::withMessages([
                    'cantidad' => 'No hay stock suficiente para registrar la salida.',
                ]);
            }

            $nuevo = $datos['tipo'] === 'entrada' ? $anterior + $cantidad : $anterior - $cantidad;

            $inventario->update(['stock' => $nuevo]);
            MovimientoInventario::create([
                'inventario_id' => $inventario->id,
                'user_id' => auth()->id(),
                'tipo' => $datos['tipo'],
                'cantidad' => $cantidad,
                'stock_anterior' => $anterior,
                'stock_nuevo' => $nuevo,
                'motivo' => $datos['motivo'] ?? null,
                'referencia' => 'Movimiento manual',
            ]);
        });

        return back()->with('success', 'Movimiento de inventario registrado.');
    }
}
