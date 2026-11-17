@extends('layouts.admin')

@section('title', 'Detalle del cliente')

@section('content')
<div class="btn-row" style="margin-bottom: 18px;">
    <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline btn-sm">← Volver</a>
    <a href="{{ route('admin.clientes.edit', $cliente) }}" class="btn btn-dark btn-sm">Editar cliente</a>
</div>

<div class="panel">
    <h1 style="font-size: 1.7rem; margin-top: 0;">{{ $cliente->nombre_completo }}</h1>
    <div class="resumen-row"><span>Correo</span><strong>{{ $cliente->user?->email ?? '—' }}</strong></div>
    <div class="resumen-row"><span>Teléfono</span><strong>{{ $cliente->telefono ?? '—' }}</strong></div>
    <div class="resumen-row"><span>Dirección</span><strong>{{ $cliente->direccion ?? '—' }}</strong></div>
    <div class="resumen-row"><span>CI</span><strong>{{ $cliente->ci ?? '—' }}</strong></div>
    <div class="resumen-row"><span>Registro</span><strong>{{ $cliente->created_at->format('d/m/Y H:i') }}</strong></div>
</div>

<div class="panel">
    <h2>Historial de pedidos</h2>
    <div class="table-wrap" style="border: 0;">
        <table>
            <thead><tr><th>Número</th><th>Fecha</th><th>Productos</th><th>Total</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($cliente->pedidos as $pedido)
                    <tr>
                        <td><strong>{{ $pedido->numero }}</strong></td>
                        <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                        <td>{{ $pedido->detalles->sum('cantidad') }} unidad(es)</td>
                        <td>Bs {{ number_format((float) $pedido->total, 2) }}</td>
                        <td><span class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span></td>
                        <td><a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-outline btn-sm">Ver</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Este cliente todavía no tiene pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
