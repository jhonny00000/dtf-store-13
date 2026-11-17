<div class="topbar">
    <div class="container">
        <span><strong>DTF STORE 13</strong> · Estampado profesional en cada prenda</span>
        <span>Envío gratis desde Bs 300 · Atención de lunes a sábado</span>
    </div>
</div>

<header class="site-header">
    <div class="container">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-mark">13</span>
            <span>DTF STORE <span style="color: var(--amarillo); display: inline;">13</span><small>URBAN PRINT</small></span>
        </a>

        <nav class="main-nav">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('catalogo.index') }}">Catálogo</a>
            <a href="{{ route('catalogo.index') }}#categorias">Categorías</a>
            <a href="{{ route('home') }}#dtf">Por qué DTF</a>
            @auth
                <a href="{{ route('pedidos.index') }}">Mis pedidos</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Panel</a>
                @endif
            @endauth
        </nav>

        <div class="nav-actions">
            <a href="{{ route('carrito.index') }}" class="cart-pill">
                Carrito
                <span class="count">{{ app(\App\Services\CarritoService::class)->cantidadTotal() }}</span>
            </a>
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Ingresar</a>
            @else
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button class="btn btn-dark btn-sm" type="submit">Salir</button>
                </form>
            @endguest
            <button class="menu-toggle" type="button" aria-label="Abrir menú">☰</button>
        </div>
    </div>
</header>
