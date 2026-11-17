@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
<div class="panel">
    <div class="panel-head">
        <h2>Categorías registradas</h2>
        <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary btn-sm">+ Nueva categoría</a>
    </div>
    <form method="GET" class="toolbar">
        <div class="field"><label for="q">Buscar</label><input id="q" type="search" name="q" value="{{ request('q') }}"></div>
        <button class="btn btn-dark btn-sm" type="submit">Filtrar</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nombre</th><th>Slug</th><th>Productos</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse ($categorias as $categoria)
                    <tr>
                        <td><strong>{{ $categoria->nombre }}</strong></td>
                        <td>{{ $categoria->slug }}</td>
                        <td>{{ $categoria->productos_count }}</td>
                        <td><span class="badge {{ $categoria->activo ? 'badge-verde' : 'badge-rojo' }}">{{ $categoria->activo ? 'Activa' : 'Inactiva' }}</span></td>
                        <td>
                            <div class="btn-row">
                                <a href="{{ route('admin.categorias.show', $categoria) }}" class="btn btn-outline btn-sm">Ver</a>
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-dark btn-sm">Editar</a>
                                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}" data-confirm="¿Eliminar la categoría {{ $categoria->nombre }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">No hay categorías registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $categorias->links() }}
</div>
@endsection
