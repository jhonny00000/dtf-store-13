<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel administrativo') | DTF Store 13</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="admin-layout">
    <aside class="admin-sidebar">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-mark">13</span>
            <span>DTF STORE 13<small>PANEL ADMIN</small></span>
        </a>

        <span class="seccion">Gestión</span>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'activo' : '' }}">Dashboard</a>
        <a href="{{ route('admin.productos.index') }}" class="{{ request()->routeIs('admin.productos.*') ? 'activo' : '' }}">Productos</a>
        <a href="{{ route('admin.categorias.index') }}" class="{{ request()->routeIs('admin.categorias.*') ? 'activo' : '' }}">Categorías</a>
        <a href="{{ route('admin.inventario.index') }}" class="{{ request()->routeIs('admin.inventario.*') ? 'activo' : '' }}">Inventario</a>
        <a href="{{ route('admin.clientes.index') }}" class="{{ request()->routeIs('admin.clientes.*') ? 'activo' : '' }}">Clientes</a>

        <span class="seccion">Operaciones</span>
        <a href="{{ route('admin.pedidos.index') }}" class="{{ request()->routeIs('admin.pedidos.*') ? 'activo' : '' }}">Pedidos y ventas</a>
        <a href="{{ route('admin.reportes.index') }}" class="{{ request()->routeIs('admin.reportes.*') ? 'activo' : '' }}">Reportes</a>

        <span class="seccion">Tienda</span>
        <a href="{{ route('home') }}">Ver tienda ↗</a>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <strong>@yield('title', 'Panel administrativo')</strong>
            <div style="display: flex; align-items: center; gap: 14px;">
                <span class="user">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline btn-sm" type="submit">Salir</button>
                </form>
            </div>
        </header>

        <div class="admin-content">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Revisa los siguientes datos:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
