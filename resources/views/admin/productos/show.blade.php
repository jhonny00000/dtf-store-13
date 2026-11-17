@extends('layouts.admin')

@section('title', 'Detalle del producto')

@section('content')
<div class="btn-row" style="margin-bottom: 18px;">
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline btn-sm">← Volver</a>
    <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-dark btn-sm">Editar producto</a>
    <a href="{{ route('catalogo.show', $producto) }}" class="btn btn-outline btn-sm">Ver en tienda ↗</a>
</div>

<div class="detalle-grid">
    <div class="panel">
        <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" style="width: 100%; border-radius: 14px;">
        @if ($producto->imagenes)
            <div class="miniaturas">
                @foreach ($producto->imagenes as $imagen)
                    <img src="{{ asset($imagen) }}" class="thumb-table" alt="Galería" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px;">
                @endforeach
            </div>
        @endif
    </div>

    <div class="panel">
        <span class="badge badge-gris">{{ $producto->categoria?->nombre }}</span>
        <h1 style="font-size: 1.7rem; margin: 12px 0;">{{ $producto->nombre }}</h1>
        <p style="color: var(--texto-suave);">{{ $producto->descripcion }}</p>
        <div class="resumen-row"><span>Precio</span><strong>Bs {{ number_format((float) $producto->precio, 2) }}</strong></div>
        <div class="resumen-row"><span>Código</span><strong>{{ $producto->codigo ?? '—' }}</strong></div>
        <div class="resumen-row"><span>Stock total</span><strong>{{ $producto->stock_total }} unidades</strong></div>
        <div class="resumen-row"><span>Estado</span><strong>{{ $producto->activo ? 'Activo' : 'Inactivo' }}</strong></div>
        <div class="resumen-row"><span>Destacado</span><strong>{{ $producto->destacado ? 'Sí' : 'No' }}</strong></div>
    </div>
</div>

<div class="panel">
    <h2>Variantes e inventario</h2>
    <div class="table-wrap" style="border: 0;">
        <table>
            <thead><tr><th>Talla</th><th>Color</th><th>Adicional</th><th>Stock</th><th>Mínimo</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @foreach ($producto->variantes as $variante)
                    <tr>
                        <td>{{ $variante->talla?->nombre }}</td>
                        <td>{{ $variante->color?->nombre }}</td>
                        <td>Bs {{ number_format((float) $variante->precio_adicional, 2) }}</td>
                        <td>{{ $variante->inventario?->stock ?? 0 }}</td>
                        <td>{{ $variante->inventario?->stock_minimo ?? 0 }}</td>
                        <td>
                            @if (($variante->inventario?->stock ?? 0) <= ($variante->inventario?->stock_minimo ?? 0))
                                <span class="badge badge-rojo">Bajo stock</span>
                            @else
                                <span class="badge badge-verde">Normal</span>
                            @endif
                        </td>
                        <td><a href="{{ route('admin.inventario.edit', $variante) }}" class="btn btn-outline btn-sm">Ajustar</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
