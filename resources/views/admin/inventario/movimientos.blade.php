@extends('layouts.admin')

@section('title', 'Movimientos de inventario')

@section('content')
<div class="panel">
    <div class="panel-head">
        <h2>Historial de entradas y salidas</h2>
        <a href="{{ route('admin.inventario.index') }}" class="btn btn-outline btn-sm">← Inventario</a>
    </div>
    <form method="GET" class="toolbar">
        <div class="field">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo" class="auto-submit">
                <option value="">Todos</option>
                <option value="entrada" @selected(request('tipo') === 'entrada')>Entrada</option>
                <option value="salida" @selected(request('tipo') === 'salida')>Salida</option>
                <option value="ajuste" @selected(request('tipo') === 'ajuste')>Ajuste</option>
                <option value="venta" @selected(request('tipo') === 'venta')>Venta</option>
            </select>
        </div>
        <button class="btn btn-dark btn-sm" type="submit">Filtrar</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Fecha</th><th>Producto / variante</th><th>Tipo</th><th>Cant.</th><th>Stock anterior</th><th>Stock nuevo</th><th>Motivo</th><th>Referencia</th></tr></thead>
            <tbody>
                @forelse ($movimientos as $movimiento)
                    <tr>
                        <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $movimiento->inventario?->variante?->producto?->nombre ?? '—' }}</strong><br><small>{{ $movimiento->inventario?->variante?->talla?->nombre ?? '' }} / {{ $movimiento->inventario?->variante?->color?->nombre ?? '' }}</small></td>
                        <td><span class="badge {{ $movimiento->tipo === 'salida' || $movimiento->tipo === 'venta' ? 'badge-rojo' : 'badge-verde' }}">{{ $movimiento->tipo_label }}</span></td>
                        <td>{{ $movimiento->cantidad }}</td>
                        <td>{{ $movimiento->stock_anterior }}</td>
                        <td>{{ $movimiento->stock_nuevo }}</td>
                        <td>{{ $movimiento->motivo ?? '—' }}</td>
                        <td>{{ $movimiento->referencia ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty">No hay movimientos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $movimientos->links() }}
</div>
@endsection
