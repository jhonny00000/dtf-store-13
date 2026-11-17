@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<p class="admin-subtitle">Resumen general de la operación de DTF Store 13.</p>

<div class="stats">
    <div class="stat amarillo"><span>Productos</span><strong>{{ $totalProductos }}</strong></div>
    <div class="stat"><span>Clientes</span><strong>{{ $totalClientes }}</strong></div>
    <div class="stat"><span>Pedidos</span><strong>{{ $totalPedidos }}</strong></div>
    <div class="stat verde"><span>Ventas registradas</span><strong>Bs {{ number_format((float) $ventas, 2) }}</strong></div>
    <div class="stat amarillo"><span>Pedidos pendientes</span><strong>{{ $pedidosPendientes }}</strong></div>
    <div class="stat rojo"><span>Bajo stock</span><strong>{{ $productosBajoStock }}</strong></div>
</div>

<div class="detalle-grid">
    <div class="panel">
        <div class="panel-head"><h2>Ventas mensuales · {{ now()->year }}</h2><a href="{{ route('admin.reportes.index') }}" class="btn btn-outline btn-sm">Ver reportes</a></div>
        @php $maximo = max($ventasMensuales->max(), 1); @endphp
        <div class="bar-chart">
            @foreach ($ventasMensuales as $indice => $monto)
                <div class="bar-item" title="Bs {{ number_format((float) $monto, 2) }}">
                    <div class="bar" style="height: {{ max(3, ((float) $monto / (float) $maximo) * 100) }}%"></div>
                    <small>{{ ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'][$indice] }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <div class="panel">
        <div class="panel-head"><h2>Últimos pedidos</h2><a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline btn-sm">Ver todos</a></div>
        <div class="table-wrap" style="border: 0;">
            <table>
                <thead><tr><th>Número</th><th>Cliente</th><th>Total</th><th>Estado</th></tr></thead>
                <tbody>
                    @forelse ($ultimosPedidos as $pedido)
                        <tr>
                            <td><a href="{{ route('admin.pedidos.show', $pedido) }}"><strong>{{ $pedido->numero }}</strong></a></td>
                            <td>{{ $pedido->cliente?->nombre ?? $pedido->user?->name ?? 'Cliente' }}</td>
                            <td>Bs {{ number_format((float) $pedido->total, 2) }}</td>
                            <td><span class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">Todavía no hay pedidos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
