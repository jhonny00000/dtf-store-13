@extends('layouts.admin')

@section('title', 'Pedidos y ventas')

@section('content')
<div class="panel">
    <h2>Listado de pedidos</h2>
    <form method="GET" class="toolbar">
        <div class="field">
            <label for="estado">Estado</label>
            <select id="estado" name="estado" class="auto-submit">
                <option value="">Todos</option>
                @foreach ($estados as $clave => $etiqueta)
                    <option value="{{ $clave }}" @selected(request('estado') === $clave)>{{ $etiqueta }}</option>
                @endforeach
            </select>
        </div>
        <div class="field"><label for="desde">Desde</label><input id="desde" type="date" name="desde" value="{{ request('desde') }}"></div>
        <div class="field"><label for="hasta">Hasta</label><input id="hasta" type="date" name="hasta" value="{{ request('hasta') }}"></div>
        <button class="btn btn-dark btn-sm" type="submit">Filtrar</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Número</th><th>Fecha</th><th>Cliente</th><th>Pago</th><th>Total</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($pedidos as $pedido)
                    <tr>
                        <td><strong>{{ $pedido->numero }}</strong></td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $pedido->cliente?->nombre ?? $pedido->user?->name ?? 'Cliente' }}</td>
                        <td>{{ $pedido->metodo_pago_label }}</td>
                        <td>Bs {{ number_format((float) $pedido->total, 2) }}</td>
                        <td><span class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span></td>
                        <td>
                            <div class="btn-row">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-outline btn-sm">Ver</a>
                                <a href="{{ route('admin.pedidos.edit', $pedido) }}" class="btn btn-dark btn-sm">Actualizar</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">No hay pedidos registrados en el periodo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $pedidos->links() }}
</div>
@endsection
