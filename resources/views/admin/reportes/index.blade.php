@extends('layouts.admin')

@section('title', 'Reportes')

@section('content')
<p class="admin-subtitle">Reportes de ventas, productos, inventario y pedidos con filtro por fechas.</p>

<div class="panel">
    <form method="GET" class="toolbar">
        <div class="field"><label for="desde">Desde</label><input id="desde" type="date" name="desde" value="{{ $desde->format('Y-m-d') }}"></div>
        <div class="field"><label for="hasta">Hasta</label><input id="hasta" type="date" name="hasta" value="{{ $hasta->format('Y-m-d') }}"></div>
        <button class="btn btn-primary btn-sm" type="submit">Generar reporte</button>
        <a href="{{ route('admin.reportes.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
    </form>
</div>

<div class="stats">
    <div class="stat verde"><span>Ventas netas</span><strong>Bs {{ number_format((float) $totalVentas, 2) }}</strong></div>
    <div class="stat amarillo"><span>Pedidos</span><strong>{{ $cantidadPedidos }}</strong></div>
    <div class="stat"><span>Ticket promedio</span><strong>Bs {{ number_format((float) $ticketPromedio, 2) }}</strong></div>
    <div class="stat"><span>Unidades vendidas</span><strong>{{ $unidadesVendidas }}</strong></div>
    <div class="stat rojo"><span>Productos con bajo stock</span><strong>{{ $inventarioBajo->count() }}</strong></div>
    <div class="stat"><span>Periodo</span><strong style="font-size: 1rem;">{{ $desde->format('d/m/Y') }} - {{ $hasta->format('d/m/Y') }}</strong></div>
</div>

<div class="detalle-grid">
    <div class="panel">
        <h2>Ventas por día</h2>
        @if ($porDia->isNotEmpty())
            <div class="table-wrap" style="border: 0;">
                <table>
                    <thead><tr><th>Fecha</th><th>Pedidos</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach ($porDia as $fecha => $datos)
                            <tr><td>{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}</td><td>{{ $datos['cantidad'] }}</td><td>Bs {{ number_format((float) $datos['total'], 2) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty">No hay ventas en el periodo seleccionado.</div>
        @endif
    </div>

    <div class="panel">
        <h2>Pedidos por estado</h2>
        <div class="table-wrap" style="border: 0;">
            <table>
                <thead><tr><th>Estado</th><th>Cantidad</th></tr></thead>
                <tbody>
                    @foreach ($porEstado as $etiqueta => $cantidad)
                        <tr><td>{{ $etiqueta }}</td><td><strong>{{ $cantidad }}</strong></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel">
    <h2>Productos más vendidos</h2>
    <div class="table-wrap" style="border: 0;">
        <table>
            <thead><tr><th>#</th><th>Producto</th><th>Unidades</th><th>Ingresos</th></tr></thead>
            <tbody>
                @forelse ($productosMasVendidos as $indice => $producto)
                    <tr>
                        <td>{{ $indice + 1 }}</td>
                        <td><strong>{{ $producto['nombre'] }}</strong></td>
                        <td>{{ $producto['cantidad'] }}</td>
                        <td>Bs {{ number_format((float) $producto['total'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">No hay productos vendidos en el periodo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <h2>Reporte de inventario · bajo stock</h2>
    <div class="table-wrap" style="border: 0;">
        <table>
            <thead><tr><th>Producto</th><th>Variante</th><th>Stock actual</th><th>Mínimo</th></tr></thead>
            <tbody>
                @forelse ($inventarioBajo as $inventario)
                    <tr>
                        <td>{{ $inventario->variante?->producto?->nombre ?? '—' }}</td>
                        <td>{{ $inventario->variante?->talla?->nombre ?? '—' }} / {{ $inventario->variante?->color?->nombre ?? '—' }}</td>
                        <td><span class="badge badge-rojo">{{ $inventario->stock }}</span></td>
                        <td>{{ $inventario->stock_minimo }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">No hay productos con bajo stock.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
