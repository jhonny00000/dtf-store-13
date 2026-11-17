@extends('layouts.app')

@php
    $variantesData = $producto->variantes
        ->filter(fn ($variante) => $variante->activo)
        ->map(fn ($variante) => [
            'id' => $variante->id,
            'talla' => $variante->talla?->nombre ?? 'Única',
            'color' => $variante->color?->nombre ?? 'Único',
            'stock' => $variante->inventario?->stock ?? 0,
        ])
        ->values();
    $tallas = $producto->variantes->pluck('talla')->filter()->unique('id')->sortBy('orden');
    $colores = $producto->variantes->pluck('color')->filter()->unique('id')->sortBy('nombre');
@endphp

@section('title', $producto->nombre.' | DTF Store 13')

@section('content')
<div class="section">
    <div class="container">
        <p style="font-size: .85rem; color: var(--texto-suave);">
            <a href="{{ route('home') }}">Inicio</a> / <a href="{{ route('catalogo.index') }}">Catálogo</a> / {{ $producto->nombre }}
        </p>

        <div class="detalle-grid">
            <div>
                <div class="galeria-principal">
                    <img id="imagen-principal" src="{{ asset($producto->imagen ?: 'images/productos/default.svg') }}" alt="{{ $producto->nombre }}">
                </div>
                <div class="miniaturas">
                    @foreach ($producto->galeria as $imagen)
                        <button type="button" class="miniatura {{ $loop->first ? 'activa' : '' }}" data-src="{{ asset($imagen) }}">
                            <img src="{{ asset($imagen) }}" alt="Vista {{ $loop->iteration }} de {{ $producto->nombre }}">
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <span class="badge badge-gris">{{ $producto->categoria?->nombre }}</span>
                <h1 style="font-size: 2.2rem; margin: 12px 0 8px; letter-spacing: -.03em;">{{ $producto->nombre }}</h1>
                <div class="precio" style="font-size: 2rem;">Bs {{ number_format((float) $producto->precio, 2) }}</div>
                <p style="color: var(--texto-suave); margin: 18px 0;">{{ $producto->descripcion }}</p>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 24px;">
                    <span class="badge {{ $producto->stock_total > 0 ? 'badge-verde' : 'badge-rojo' }}">
                        {{ $producto->stock_total > 0 ? 'Disponible · '.$producto->stock_total.' unidades' : 'Agotado' }}
                    </span>
                    @if ($producto->codigo)<span class="badge badge-amarillo">Código {{ $producto->codigo }}</span>@endif
                </div>

                <form method="POST" action="{{ route('carrito.agregar') }}">
                    @csrf
                    <input type="hidden" name="variante_id" id="variante_id" value="">

                    <div class="field" style="margin-bottom: 18px;">
                        <label>Talla</label>
                        <div class="opciones">
                            @foreach ($tallas as $talla)
                                <div class="opcion">
                                    <input type="radio" id="talla-{{ $talla->id }}" name="talla" value="{{ $talla->nombre }}" @checked($loop->first)>
                                    <label for="talla-{{ $talla->id }}">{{ $talla->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="field" style="margin-bottom: 18px;">
                        <label>Color</label>
                        <div class="opciones">
                            @foreach ($colores as $color)
                                <div class="opcion">
                                    <input type="radio" id="color-{{ $color->id }}" name="color" value="{{ $color->nombre }}" @checked($loop->first)>
                                    <label for="color-{{ $color->id }}"><span class="color-dot" style="background: {{ $color->codigo }}"></span>{{ $color->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="field" style="margin-bottom: 12px;">
                        <label for="cantidad">Cantidad</label>
                        <div class="cantidad-control" data-cantidad>
                            <button type="button" data-accion="restar" aria-label="Disminuir">−</button>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1">
                            <button type="button" data-accion="sumar" aria-label="Aumentar">+</button>
                        </div>
                    </div>

                    <p id="stock-variante" style="font-size: .88rem; color: var(--texto-suave); min-height: 22px;"></p>

                    <button type="submit" id="agregar-carrito" class="btn btn-primary btn-block" style="padding: 15px; font-size: 1rem;">
                        Agregar al carrito
                    </button>
                </form>

                <div style="margin-top: 22px; border-top: 1px solid var(--gris-medio); padding-top: 18px; font-size: .88rem; color: var(--texto-suave);">
                    <strong>Detalles:</strong> Estampado DTF · Tallas S a XXL · Precio final en bolivianos · Las tallas XL y XXL tienen un adicional de Bs 3 y Bs 6 respectivamente.
                </div>
            </div>
        </div>

        @if ($relacionados->isNotEmpty())
            <div style="margin-top: 64px;">
                <div class="section-head"><div><h2>También te puede gustar</h2></div></div>
                <div class="grid grid-4">
                    @foreach ($relacionados as $productoRelacionado)
                        @include('partials.producto-card', ['producto' => $productoRelacionado])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script type="application/json" id="variantes-data">@json($variantesData)</script>
@endpush
@endsection
