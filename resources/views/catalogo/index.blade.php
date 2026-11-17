@extends('layouts.app')

@section('title', 'Catálogo | DTF Store 13')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Catálogo DTF</h1>
        <p>Poleras, hoodies y polos con estampado profesional. Filtra, compara y elige tu estilo.</p>
    </div>
</div>

<div class="section">
    <div class="container">
        <form method="GET" action="{{ route('catalogo.index') }}" class="filtros">
            <div class="field">
                <label for="q">Buscar</label>
                <input type="search" id="q" name="q" value="{{ $filtros['q'] ?? '' }}" placeholder="Nombre, descripción o código">
            </div>
            <div class="field">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" class="auto-submit">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->slug }}" @selected(($filtros['categoria'] ?? '') === $categoria->slug)>{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="orden">Ordenar por</label>
                <select id="orden" name="orden" class="auto-submit">
                    <option value="nombre_asc" @selected(($filtros['orden'] ?? '') === 'nombre_asc')>Nombre (A-Z)</option>
                    <option value="nombre_desc" @selected(($filtros['orden'] ?? '') === 'nombre_desc')>Nombre (Z-A)</option>
                    <option value="precio_asc" @selected(($filtros['orden'] ?? '') === 'precio_asc')>Precio (menor a mayor)</option>
                    <option value="precio_desc" @selected(($filtros['orden'] ?? '') === 'precio_desc')>Precio (mayor a menor)</option>
                </select>
            </div>
            <label class="check">
                <input type="checkbox" name="disponibles" value="1" @checked(($filtros['disponibles'] ?? '') === '1')>
                Solo disponibles
            </label>
            <div style="grid-column: 1 / -1; display: flex; gap: 10px;">
                <button class="btn btn-primary btn-sm" type="submit">Aplicar filtros</button>
                <a href="{{ route('catalogo.index') }}" class="btn btn-outline btn-sm">Limpiar</a>
            </div>
        </form>

        <p style="color: var(--texto-suave); font-size: .9rem;">{{ $productos->total() }} producto(s) encontrado(s)</p>

        <div class="grid grid-4">
            @forelse ($productos as $producto)
                @include('partials.producto-card', ['producto' => $producto])
            @empty
                <div class="empty" style="grid-column: 1 / -1;">
                    <h3>No encontramos productos</h3>
                    <p>Prueba con otra búsqueda o elimina los filtros aplicados.</p>
                </div>
            @endforelse
        </div>

        {{ $productos->links() }}
    </div>
</div>
@endsection
