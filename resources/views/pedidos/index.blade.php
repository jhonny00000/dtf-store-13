@extends('layouts.app')

@section('title', 'Mis pedidos | DTF Store 13')

@section('content')
<div class="section">
    <div class="container">
        <div class="section-head"><div><h1>Mis pedidos</h1><p>Historial de compras registradas en DTF Store 13.</p></div></div>

        <div class="table-wrap">
            <table>
                <thead><tr><th>Número</th><th>Fecha</th><th>Productos</th><th>Total</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    @forelse ($pedidos as $pedido)
                        <tr>
                            <td><strong>{{ $pedido->numero }}</strong></td>
                            <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                            <td>{{ $pedido->detalles->sum('cantidad') }} unidad(es)</td>
                            <td>Bs {{ number_format((float) $pedido->total, 2) }}</td>
                            <td><span class="estado estado-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span></td>
                            <td><a href="{{ route('pedidos.show', $pedido) }}" class="btn btn-outline btn-sm">Ver detalle</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Aún no tienes pedidos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pedidos->links() }}
    </div>
</div>
@endsection
