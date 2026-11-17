@extends('layouts.app')

@section('title', 'DTF Store 13 | Poleras personalizadas con estampado DTF')

@section('content')
<section class="hero">
    <div class="container">
        <div>
            <span class="eyebrow">Estampado DTF · Bolivia</span>
            <h1>Tu estilo, <em>impreso</em> para durar.</h1>
            <p>Poleras personalizadas con tecnología DTF: colores vibrantes, detalles nítidos y acabados que resisten el uso diario. Diseñamos prendas urbanas con la identidad del equipo 13.</p>
            <div class="btn-row" style="margin-top: 28px;">
                <a href="{{ route('catalogo.index') }}" class="btn btn-primary">Explorar catálogo</a>
                <a href="#dtf" class="btn btn-light">Conocer el estampado</a>
            </div>
            <div style="display: flex; gap: 28px; margin-top: 36px; flex-wrap: wrap;">
                <div><strong style="display: block; font-size: 1.5rem; color: var(--amarillo);">+900</strong><small>Diseños producidos</small></div>
                <div><strong style="display: block; font-size: 1.5rem; color: var(--amarillo);">100%</strong><small>Colores intensos</small></div>
                <div><strong style="display: block; font-size: 1.5rem; color: var(--amarillo);">13</strong><small>Equipo creativo</small></div>
            </div>
        </div>
        <div class="hero-img">
            <img src="{{ asset('images/productos/graffiti-negra.svg') }}" alt="Polera urbana con estampado DTF">
            <div class="hero-stamp">NUEVA COLECCIÓN</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Lo más vendido</span>
                <h2>Productos destacados</h2>
                <p>Diseños que ya forman parte del streetwear de nuestros clientes.</p>
            </div>
            <a href="{{ route('catalogo.index') }}" class="btn btn-outline">Ver todo el catálogo →</a>
        </div>
        <div class="grid grid-4">
            @forelse ($destacados as $producto)
                @include('partials.producto-card', ['producto' => $producto])
            @empty
                <div class="empty" style="grid-column: 1 / -1;">
                    <h3>Próximamente nuevos diseños</h3>
                    <p>Ejecuta los seeders para cargar el catálogo de demostración.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="section section-gris" id="categorias">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Encuentra tu estilo</span>
                <h2>Categorías</h2>
            </div>
        </div>
        <div class="grid grid-4">
            @foreach ($categorias as $categoria)
                <a href="{{ route('catalogo.index', ['categoria' => $categoria->slug]) }}" class="category-card">
                    <strong>{{ $categoria->nombre }}</strong>
                    <span>{{ $categoria->productos_count }} producto(s)</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section section-dark" id="dtf">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Tecnología DTF</span>
                <h2>¿Por qué elegir estampado DTF?</h2>
                <p>Transferencia directa a film que combina definición, durabilidad y libertad de diseño.</p>
            </div>
        </div>
        <div class="grid grid-3">
            <div class="ventaja"><h3>Colores vibrantes</h3><p>Reproducción fiel de degradados y detalles con una intensidad difícil de lograr con otros métodos.</p></div>
            <div class="ventaja"><h3>Mayor durabilidad</h3><p>El estampado resiste lavados y uso frecuente sin agrietarse ni perder adherencia.</p></div>
            <div class="ventaja"><h3>Sobre muchas telas</h3><p>Algodón, poliéster y mezclas: el DTF se adapta a distintas prendas con excelente resultado.</p></div>
            <div class="ventaja"><h3>Textura suave</h3><p>Acabado flexible que no endurece la zona impresa y mantiene la comodidad de la prenda.</p></div>
            <div class="ventaja"><h3>Diseños personalizados</h3><p>Logotipos, tipografías e ilustraciones complejas producidas con altos estándares.</p></div>
            <div class="ventaja"><h3>Producción ágil</h3><p>Ideal para pedidos pequeños, series y colecciones urbanas con tiempos cortos de entrega.</p></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="promo">
            <div>
                <span class="badge badge-gris">Promoción vigente</span>
                <h2>Envío gratis desde Bs 300</h2>
                <p>Combina tus diseños favoritos y recibe tu pedido sin costo de envío en la ciudad.</p>
            </div>
            <a href="{{ route('catalogo.index') }}" class="btn btn-dark">Aprovechar promoción</a>
        </div>
    </div>
</section>

<section class="section section-gris">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Recién llegados</span>
                <h2>Novedades</h2>
            </div>
        </div>
        <div class="grid grid-4">
            @foreach ($novedades as $producto)
                @include('partials.producto-card', ['producto' => $producto])
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="eyebrow">Estamos cerca</span>
                <h2>Hablemos de tu diseño</h2>
            </div>
        </div>
        <div class="contact-grid">
            <div class="contact-item"><span>Correo</span><strong>ventas@dtfstore13.local</strong></div>
            <div class="contact-item"><span>Teléfono</span><strong>+591 70000013</strong></div>
            <div class="contact-item"><span>Horario</span><strong>Lunes a sábado · 09:00 - 19:00</strong></div>
        </div>
    </div>
</section>
@endsection
