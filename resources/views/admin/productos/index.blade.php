@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
<div class="panel">
    <div class="panel-head">
        <h2>Listado de productos</h2>
        <a href="{{ route('admin.productos.create') }}" class="btn btn-primary btn-sm">+ Nuevo producto</a>
    </div>

    <form method="GET" class="toolbar">
        <div class="field"><label for="q">Buscar</label><input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Nombre o código"></div>
        <div class="field">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria" class="auto-submit">
                <option value="">Todas</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(request('categoria') == $categoria->id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-dark btn-sm" type="submit">Filtrar</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Imagen</th><th>Producto</th><th>Categoría</th><th>Precio</th><th>Variantes</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr>
                        <td><img src="{{ asset($producto->imagen) }}" class="thumb-table" alt=""></td>
                        <td><strong>{{ $producto->nombre }}</strong><br><small style="color: var(--texto-suave);">{{ $producto->codigo ?? $producto->slug }}</small></td>
                        <td>{{ $producto->categoria?->nombre }}</td>
                        <td>Bs {{ number_format((float) $producto->precio, 2) }}</td>
                        <td>{{ $producto->variantes_count }}</td>
                        <td>
                            <span class="badge {{ $producto->activo ? 'badge-verde' : 'badge-rojo' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span>
                            @if ($producto->destacado)<br><span class="badge badge-amarillo" style="margin-top: 4px;">Destacado</span>@endif
                        </td>
                        <td>
                            <div class="btn-row">
                                <a href="{{ route('admin.productos.show', $producto) }}" class="btn btn-outline btn-sm">Ver</a>
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-dark btn-sm">Editar</a>
                                <form method="POST" action="{{ route('admin.productos.estado', $producto) }}">
                                    @csrf
                                    <button class="btn btn-outline btn-sm" type="submit">{{ $producto->activo ? 'Desactivar' : 'Activar' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" data-confirm="¿Eliminar el producto {{ $producto->nombre }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty">No hay productos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $productos->links() }}
</div>
@endsection
