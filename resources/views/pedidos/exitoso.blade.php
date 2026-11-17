@extends('layouts.app')

@section('title', 'Pedido confirmado | DTF Store 13')

@section('content')
<div class="section">
    <div class="container-sm">
        <div class="auth-card" style="text-align: center;">
            <div style="width: 76px; height: 76px; margin: 0 auto 18px; border-radius: 50%; background: var(--amarillo); color: var(--negro); display: grid; place-items: center; font-size: 2rem; font-weight: 900;">✓</div>
            <span class="eyebrow">Pedido registrado</span>
            <h1>¡Gracias por tu compra!</h1>
            <p>Tu pedido <strong>{{ $pedido->numero }}</strong> fue registrado correctamente. Puedes seguir su estado desde la sección Mis pedidos.</p>

            <div class="resumen-card" style="text-align: left; margin: 24px 0; position: static;">
                <div class="resumen-row"><span>Número de pedido</span><strong>{{ $pedido->numero }}</strong></div>
                <div class="resumen-row"><span>Fecha</span><strong>{{ $pedido->created_at->format('d/m/Y H:i') }}</strong></div>
                <div class="resumen-row"><span>Estado</span><strong class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</strong></div>
                <div class="resumen-row"><span>Método de pago</span><strong>{{ $pedido->metodo_pago_label }}</strong></div>
                <div class="resumen-row total"><span>Total</span><strong>Bs {{ number_format((float) $pedido->total, 2) }}</strong></div>
            </div>

            <div class="btn-row" style="justify-content: center;">
                <a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-primary">Ver detalle del pedido</a>
                <a href="{{ route('catalogo.index') }}" class="btn btn-outline">Seguir comprando</a>
            </div>
        </div>
    </div>
</div>
@endsection
