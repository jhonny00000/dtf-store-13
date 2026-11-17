@extends('layouts.admin')

@section('title', 'Inventario')

@section('content')
<div class="panel">
    <div class="panel-head">
        <h2>Control de existencias</h2>
        <div class="btn-row">
            <a href="{{ route('admin.inventario.movimientos') }}" class="btn btn-outline btn-sm">Ver movimientos</a>
            <span class="badge badge-rojo">{{ $bajoStock }} variante(s) con bajo stock</span>
        </div>
    </div>

    <form method="GET" class="toolbar">
        <div class="field"><label for="q">Buscar producto</label><input id="q" type="search" name="q" value="{{ request('q') }}"></div>
        <label class="checkbox-line" style="padding-bottom: 10px;"><input type="checkbox" name="bajo" value="1" @checked(request('bajo') === '1')> Solo bajo stock</label>
        <button class="btn btn-dark btn-sm" type="submit">Filtrar</button>
    </form>

    <div class="table-wrap">
        <table>
            <thead><tr><th>Producto</th><th>Variante</th><th>Stock</th><th>Mínimo</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                @forelse ($inventarios as $inventario)
                    <tr>
                        <td><strong>{{ $inventario->variante?->producto?->nombre ?? 'Producto eliminado' }}</strong></td>
                        <td>{{ $inventario->variante?->talla?->nombre ?? '—' }} / {{ $inventario->variante?->color?->nombre ?? '—' }}</td>
                        <td><strong>{{ $inventario->stock }}</strong></td>
                        <td>{{ $inventario->stock_minimo }}</td>
                        <td>
                            @if ($inventario->bajo_stock)
                                <span class="badge badge-rojo">Bajo stock</span>
                            @else
                                <span class="badge badge-verde">Normal</span>
                            @endif
                        </td>
                        <td>
                            @if ($inventario->variante)
                                <a href="{{ route('admin.inventario.edit', $inventario->variante) }}" class="btn btn-outline btn-sm">Ajustar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No hay registros de inventario.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
