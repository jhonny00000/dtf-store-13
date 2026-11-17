@extends('layouts.admin')

@section('title', 'Clientes')

@section('content')
<div class="panel">
    <div class="panel-head">
        <h2>Clientes registrados</h2>
        <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary btn-sm">+ Registrar cliente</a>
    </div>
    <form method="GET" class="toolbar">
        <div class="field"><label for="q">Buscar</label><input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Nombre, teléfono o correo"></div>
        <button class="btn btn-dark btn-sm" type="submit">Filtrar</button>
    </form>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Cliente</th><th>Correo</th><th>Teléfono</th><th>Pedidos</th><th>Registro</th><th></th></tr></thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td><strong>{{ $cliente->nombre_completo }}</strong></td>
                        <td>{{ $cliente->user?->email ?? '—' }}</td>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                        <td>{{ $cliente->pedidos_count ?? $cliente->pedidos()->count() }}</td>
                        <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-row">
                                <a href="{{ route('admin.clientes.show', $cliente) }}" class="btn btn-outline btn-sm">Ver</a>
                                <a href="{{ route('admin.clientes.edit', $cliente) }}" class="btn btn-dark btn-sm">Editar</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">No hay clientes registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $clientes->links() }}
</div>
@endsection
