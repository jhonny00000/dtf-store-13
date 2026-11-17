@extends('layouts.app')

@section('title', 'Pedido '.$pedido->numero.' | DTF Store 13')

@section('content')
<div class="section">
    <div class="container">
        <div class="section-head">
            <div><h1>Pedido {{ $pedido->numero }}</h1><p>Registrado el {{ $pedido->created_at->format('d/m/Y H:i') }}</p></div>
            <span class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span>
        </div>

        <div class="detalle-grid">
            <div class="panel">
                <h2>Productos</h2>
                <div class="table-wrap" style="border: 0;">
                    <table>
                        <thead><tr><th>Producto</th><th>Variante</th><th>Cant.</th><th>Precio unit.</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($pedido->detalles as $detalle)
                                <tr>
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

            <div>
                <div class="panel">
                    <h2>Entrega y pago</h2>
                    <div class="resumen-row"><span>Dirección</span><strong style="text-align: right; max-width: 60%;">{{ $pedido->direccion_entrega }}</strong></div>
                    <div class="resumen-row"><span>Método de pago</span><strong>{{ $pedido->metodo_pago_label }}</strong></div>
                    <div class="resumen-row"><span>Estado del pago</span><strong>{{ $pedido->pago?->estado_label ?? 'Sin registro' }}</strong></div>
                    @if ($pedido->notas)<div class="resumen-row"><span>Notas</span><strong>{{ $pedido->notas }}</strong></div>@endif
                    <div class="resumen-row"><span>Subtotal</span><strong>Bs {{ number_format((float) $pedido->subtotal, 2) }}</strong></div>
                    <div class="resumen-row"><span>Envío</span><strong>{{ (float) $pedido->costo_envio > 0 ? 'Bs '.number_format((float) $pedido->costo_envio, 2) : 'Gratis' }}</strong></div>
                    <div class="resumen-row total"><span>Total</span><strong>Bs {{ number_format((float) $pedido->total, 2) }}</strong></div>
                </div>
                <a href="{{ route('pedidos.index') }}" class="btn btn-outline">← Volver a mis pedidos</a>
            </div>
        </div>
    </div>
</div>
@endsection
