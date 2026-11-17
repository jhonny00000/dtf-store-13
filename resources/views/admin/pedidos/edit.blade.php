@extends('layouts.admin')

@section('title', 'Actualizar pedido')

@section('content')
<p class="admin-subtitle">Pedido {{ $pedido->numero }} · Total Bs {{ number_format((float) $pedido->total, 2) }}</p>

<div class="panel container-sm" style="width: min(680px, 100%);">
    @if ($pedido->estado === 'cancelado')
        <div class="alert alert-error">Este pedido está cancelado y no puede reactivarse desde este módulo.</div>
    @endif
    <form method="POST" action="{{ route('admin.pedidos.update', $pedido) }}">
        @csrf
        @method('PUT')
        <div class="field" style="margin-bottom: 16px;">
            <label for="estado">Estado del pedido *</label>
            <select id="estado" name="estado" required>
                @foreach ($estados as $clave => $etiqueta)
                    <option value="{{ $clave }}" @selected(old('estado', $pedido->estado) === $clave)>{{ $etiqueta }}</option>
                @endforeach
            </select>
            @error('estado')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom: 16px;">
            <label for="pago_estado">Estado del pago *</label>
            <select id="pago_estado" name="pago_estado" required>
                <option value="pendiente" @selected(old('pago_estado', $pedido->pago?->estado) === 'pendiente')>Pendiente</option>
                <option value="pagado" @selected(old('pago_estado', $pedido->pago?->estado) === 'pagado')>Pagado</option>
                <option value="rechazado" @selected(old('pago_estado', $pedido->pago?->estado) === 'rechazado')>Rechazado</option>
            </select>
            @error('pago_estado')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom: 18px;">
            <label for="referencia">Referencia de pago (opcional)</label>
            <input id="referencia" type="text" name="referencia" value="{{ old('referencia', $pedido->pago?->referencia) }}" maxlength="100">
        </div>
        <div class="alert alert-success">Al cancelar un pedido, el stock de sus productos se devuelve automáticamente al inventario.</div>
        <div class="btn-row">
            <button class="btn btn-primary" type="submit">Guardar actualización</button>
            <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
