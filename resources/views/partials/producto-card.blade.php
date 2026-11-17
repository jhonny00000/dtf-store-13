@php
    $disponible = ($producto->stock_total ?? 0) > 0;
@endphp

<article class="product-card">
    <a href="{{ route('catalogo.show', $producto) }}" class="product-card-img">
        <img src="{{ asset($producto->imagen ?: 'images/productos/default.svg') }}" alt="{{ $producto->nombre }}" loading="lazy">
    </a>
    <div class="product-card-body">
        <div style="display: flex; justify-content: space-between; gap: 8px; align-items: center;">
            <span class="badge badge-gris">{{ $producto->categoria?->nombre ?? 'Poleras' }}</span>
            @if ($producto->destacado)
                <span class="badge badge-amarillo">Destacado</span>
            @endif
        </div>
        <h3><a href="{{ route('catalogo.show', $producto) }}">{{ $producto->nombre }}</a></h3>
        <p>{{ $producto->descripcion }}</p>
        <div class="precio">Bs {{ number_format((float) $producto->precio, 2) }}</div>
        <span class="badge {{ $disponible ? 'badge-verde' : 'badge-rojo' }}">
            {{ $disponible ? 'Disponible · '.$producto->stock_total.' und.' : 'Agotado' }}
        </span>
        <a href="{{ route('catalogo.show', $producto) }}" class="btn btn-dark btn-sm btn-block" style="margin-top: auto;">Ver producto</a>
    </div>
</article>
