@extends('layouts.admin')

@section('title', 'Detalle del pedido')

@section('content')
<div class="btn-row" style="margin-bottom: 18px;">
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline btn-sm">← Volver</a>
    <a href="{{ route('admin.pedidos.edit', $pedido) }}" class="btn btn-primary btn-sm">Actualizar estado</a>
</div>

<div class="detalle-grid">
    <div class="panel">
        <div class="panel-head"><h2>{{ $pedido->numero }}</h2><span class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span></div>
        <div class="resumen-row"><span>Fecha</span><strong>{{ $pedido->created_at->format('d/m/Y H:i') }}</strong></div>
        <div class="resumen-row"><span>Cliente</span><strong>{{ $pedido->cliente?->nombre_completo ?? $pedido->user?->name ?? '—' }}</strong></div>
        <div class="resumen-row"><span>Correo</span><strong>{{ $pedido->cliente?->user?->email ?? $pedido->user?->email ?? '—' }}</strong></div>
        <div class="resumen-row"><span>Dirección de entrega</span><strong style="text-align: right; max-width: 65%;">{{ $pedido->direccion_entrega }}</strong></div>
        <div class="resumen-row"><span>Método de pago</span><strong>{{ $pedido->metodo_pago_label }}</strong></div>
        <div class="resumen-row"><span>Estado del pago</span><strong>{{ $pedido->pago?->estado_label ?? 'Sin registro' }}</strong></div>
        @if ($pedido->pago?->referencia)<div class="resumen-row"><span>Referencia</span><strong>{{ $pedido->pago->referencia }}</strong></div>@endif
        @if ($pedido->notas)<div class="resumen-row"><span>Notas</span><strong>{{ $pedido->notas }}</strong></div>@endif
    </div>

    <div class="panel">
        <h2>Resumen</h2>
        <div class="resumen-row"><span>Subtotal</span><strong>Bs {{ number_format((float) $pedido->subtotal, 2) }}</strong></div>
        <div class="resumen-row"><span>Envío</span><strong>{{ (float) $pedido->costo_envio > 0 ? 'Bs '.number_format((float) $pedido->costo_envio, 2) : 'Gratis' }}</strong></div>
        <div class="resumen-row total"><span>Total</span><strong>Bs {{ number_format((float) $pedido->total, 2) }}</strong></div>
    </div>
</div>

<div class="panel">
    <h2>Productos comprados</h2>
    <div class="table-wrap" style="border: 0;">
        <table>
            <thead><tr><th>Imagen</th><th>Producto</th><th>Variante</th><th>Cantidad</th><th>Precio histórico</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach ($pedido->detalles as $detalle)
                    <tr>
                        <td><img src="{{ asset($detalle->imagen ?: 'images/productos/default.svg') }}" class="thumb-table" alt=""></td>
                        <td><strong>{{ $detalle->nombre_producto }}</strong></td>
                        <td>{{ $detalle->talla_nombre }} / {{ $detalle->color_nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>Bs {{ number_format((float) $detalle->precio_unitario, 2) }}</td>
                        <td>Bs {{ number_format((float) $detalle->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
