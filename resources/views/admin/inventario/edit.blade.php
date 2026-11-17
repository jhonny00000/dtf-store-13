@extends('layouts.admin')

@section('title', 'Ajustar inventario')

@section('content')
<p class="admin-subtitle">{{ $variante->producto?->nombre }} · {{ $variante->talla?->nombre }} / {{ $variante->color?->nombre }}</p>

<div class="detalle-grid">
    <div class="panel">
        <h2>Configuración de la variante</h2>
        <form method="POST" action="{{ route('admin.inventario.update', $variante) }}">
            @csrf
            @method('PUT')
            <div class="field" style="margin-bottom: 14px;">
                <label for="stock">Stock actual *</label>
                <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $variante->inventario?->stock ?? 0) }}" required>
                @error('stock')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field" style="margin-bottom: 14px;">
                <label for="stock_minimo">Stock mínimo *</label>
                <input type="number" id="stock_minimo" name="stock_minimo" min="0" value="{{ old('stock_minimo', $variante->inventario?->stock_minimo ?? 5) }}" required>
                @error('stock_minimo')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field" style="margin-bottom: 14px;">
                <label for="precio_adicional">Precio adicional (Bs) *</label>
                <input type="number" id="precio_adicional" name="precio_adicional" step="0.01" min="0" value="{{ old('precio_adicional', $variante->precio_adicional) }}" required>
                @error('precio_adicional')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <label class="checkbox-line" style="margin-bottom: 20px;">
                <input type="checkbox" name="activo" value="1" @checked(old('activo', $variante->activo))> Variante activa para la venta
            </label>
            <button class="btn btn-primary" type="submit">Guardar ajuste</button>
        </form>
    </div>

    <div class="panel">
        <h2>Registrar entrada o salida</h2>
        <form method="POST" action="{{ route('admin.inventario.movimiento', $variante) }}">
            @csrf
            <div class="field" style="margin-bottom: 14px;">
                <label for="tipo">Tipo de movimiento *</label>
                <select id="tipo" name="tipo" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
                @error('tipo')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field" style="margin-bottom: 14px;">
                <label for="cantidad">Cantidad *</label>
                <input type="number" id="cantidad" name="cantidad" min="1" value="{{ old('cantidad', 1) }}" required>
                @error('cantidad')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field" style="margin-bottom: 18px;">
                <label for="motivo">Motivo</label>
                <input type="text" id="motivo" name="motivo" value="{{ old('motivo') }}" maxlength="200">
            </div>
            <button class="btn btn-dark" type="submit">Registrar movimiento</button>
        </form>
        <div class="resumen-row" style="margin-top: 22px;"><span>Stock anterior registrado</span><strong>{{ $variante->inventario?->stock ?? 0 }}</strong></div>
    </div>
</div>

<div class="btn-row" style="margin-top: 18px;">
    <a href="{{ route('admin.inventario.index') }}" class="btn btn-outline">← Volver al inventario</a>
    <a href="{{ route('admin.inventario.movimientos') }}" class="btn btn-outline">Ver movimientos</a>
</div>
@endsection
