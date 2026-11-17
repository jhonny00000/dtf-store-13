@extends('layouts.app')

@section('title', 'Confirmar compra | DTF Store 13')

@section('content')
<div class="section">
    <div class="container">
        <div class="section-head"><div><h1>Confirmar compra</h1><p>Revisa tus datos y elige cómo deseas pagar. No se procesan pagos bancarios en esta versión.</p></div></div>

        <div class="detalle-grid">
            <div class="panel">
                <h2>Datos de entrega</h2>
                <form method="POST" action="{{ route('pedidos.store') }}">
                    @csrf
                    <div class="field" style="margin-bottom: 16px;">
                        <label for="direccion_entrega">Dirección de entrega *</label>
                        <textarea id="direccion_entrega" name="direccion_entrega" required>{{ old('direccion_entrega', auth()->user()->cliente?->direccion) }}</textarea>
                        @error('direccion_entrega')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field" style="margin-bottom: 16px;">
                        <label for="metodo_pago">Método de pago *</label>
                        <select id="metodo_pago" name="metodo_pago" required>
                            @foreach ($metodos as $clave => $etiqueta)
                                <option value="{{ $clave }}" @selected(old('metodo_pago') === $clave)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('metodo_pago')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field" style="margin-bottom: 18px;">
                        <label for="notas">Notas para el vendedor (opcional)</label>
                        <textarea id="notas" name="notas">{{ old('notas') }}</textarea>
                    </div>
                    <div class="alert alert-success" style="margin-top: 0;">El pago quedará registrado como pendiente. El administrador confirmará la operación.</div>
                    <button type="submit" class="btn btn-primary btn-block" style="padding: 14px;">Registrar pedido</button>
                </form>
            </div>

            <div class="resumen-card">
                <h2 style="margin-top: 0;">Tu pedido</h2>
                @foreach ($items as $item)
                    <div class="resumen-row">
                        <span>{{ $item['variante']->producto->nombre }} <small>({{ $item['variante']->talla?->nombre }}/{{ $item['variante']->color?->nombre }}) ×{{ $item['cantidad'] }}</small></span>
                        <strong>Bs {{ number_format($item['subtotal'], 2) }}</strong>
                    </div>
                @endforeach
                <div class="resumen-row"><span>Subtotal</span><strong>Bs {{ number_format($subtotal, 2) }}</strong></div>
                <div class="resumen-row"><span>Envío</span><strong>{{ $costoEnvio > 0 ? 'Bs '.number_format($costoEnvio, 2) : 'Gratis' }}</strong></div>
                <div class="resumen-row total"><span>Total</span><strong>Bs {{ number_format($total, 2) }}</strong></div>
                <a href="{{ route('carrito.index') }}" class="btn btn-outline btn-block btn-sm" style="margin-top: 16px;">← Editar carrito</a>
            </div>
        </div>
    </div>
</div>
@endsection
