@extends('layouts.app')

@section('title', 'Carrito | DTF Store 13')

@section('content')
<div class="section">
    <div class="container">
        <div class="section-head"><div><h1>Tu carrito</h1><p>Revisa cantidades y disponibilidad antes de confirmar tu pedido.</p></div></div>

        @if ($items === [])
            <div class="empty">
                <h3>Tu carrito está vacío</h3>
                <p>Explora el catálogo y encuentra la polera perfecta para ti.</p>
                <a href="{{ route('catalogo.index') }}" class="btn btn-primary" style="margin-top: 14px;">Ir al catálogo</a>
            </div>
        @else
            <div class="detalle-grid">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr><th>Producto</th><th>Variante</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                @php $variante = $item['variante']; @endphp
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <img src="{{ asset($variante->producto->imagen) }}" class="thumb-table" alt="{{ $variante->producto->nombre }}">
                                            <div><strong>{{ $variante->producto->nombre }}</strong><br><small style="color: var(--texto-suave);">Stock: {{ $item['stock'] }}</small></div>
                                        </div>
                                    </td>
                                    <td>{{ $variante->talla?->nombre }} / {{ $variante->color?->nombre }}</td>
                                    <td>Bs {{ number_format($item['precio_unitario'], 2) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('carrito.actualizar', $variante) }}" style="display: flex; gap: 6px;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" max="{{ $item['stock'] }}" class="form-control" style="width: 72px;">
                                            <button class="btn btn-dark btn-sm" type="submit">OK</button>
                                        </form>
                                    </td>
                                    <td><strong>Bs {{ number_format($item['subtotal'], 2) }}</strong></td>
                                    <td>
                                        <form method="POST" action="{{ route('carrito.eliminar', $variante) }}" data-confirm="¿Eliminar este producto del carrito?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    <div class="resumen-card">
                        <h2 style="margin-top: 0;">Resumen de compra</h2>
                        <div class="resumen-row"><span>Subtotal</span><strong>Bs {{ number_format($subtotal, 2) }}</strong></div>
                        <div class="resumen-row"><span>Envío</span><strong>{{ $costoEnvio > 0 ? 'Bs '.number_format($costoEnvio, 2) : 'Gratis' }}</strong></div>
                        <div class="resumen-row total"><span>Total</span><strong>Bs {{ number_format($total, 2) }}</strong></div>
                        @auth
                            <a href="{{ route('pedidos.checkout') }}" class="btn btn-primary btn-block" style="margin-top: 20px; padding: 14px;">Confirmar compra</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-block" style="margin-top: 20px; padding: 14px;">Ingresa para continuar</a>
                            <p style="font-size: .8rem; text-align: center; margin-top: 10px;">¿No tienes cuenta? <a href="{{ route('registro') }}" style="color: var(--amarillo); font-weight: 700;">Regístrate aquí</a></p>
                        @endauth
                        <form method="POST" action="{{ route('carrito.vaciar') }}" data-confirm="¿Vaciar todo el carrito?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline btn-block btn-sm" style="margin-top: 10px;">Vaciar carrito</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
