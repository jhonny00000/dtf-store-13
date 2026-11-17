@extends('layouts.admin')

@section('title', 'Detalle de categoría')

@section('content')
<div class="btn-row" style="margin-bottom: 18px;">
    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline btn-sm">← Volver</a>
    <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-dark btn-sm">Editar</a>
</div>

<div class="panel">
    <span class="badge badge-gris">{{ $categoria->slug }}</span>
    <h1 style="font-size: 1.8rem; margin: 12px 0;">{{ $categoria->nombre }}</h1>
    <p style="color: var(--texto-suave);">{{ $categoria->descripcion ?: 'Sin descripción registrada.' }}</p>
    <div class="resumen-row"><span>Estado</span><strong>{{ $categoria->activo ? 'Activa' : 'Inactiva' }}</strong></div>
    <div class="resumen-row"><span>Productos asociados</span><strong>{{ $categoria->productos->count() }}</strong></div>
</div>

<div class="panel">
    <h2>Productos de esta categoría</h2>
    <div class="table-wrap" style="border: 0;">
        <table>
            <thead><tr><th>Producto</th><th>Precio</th><th>Estado</th></tr></thead>
            <tbody>
                @forelse ($categoria->productos as $producto)
                    <tr>
                        <td><a href="{{ route('admin.productos.show', $producto) }}"><strong>{{ $producto->nombre }}</strong></a></td>
                        <td>Bs {{ number_format((float) $producto->precio, 2) }}</td>
                        <td><span class="badge {{ $producto->activo ? 'badge-verde' : 'badge-rojo' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">Esta categoría todavía no tiene productos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
