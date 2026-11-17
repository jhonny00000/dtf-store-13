@extends('layouts.admin')

@section('title', 'Editar cliente')

@section('content')
<p class="admin-subtitle">Actualiza la información de contacto del cliente.</p>
<div class="panel container-sm" style="width: min(680px, 100%);">
    <form method="POST" action="{{ route('admin.clientes.update', $cliente) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="nombre">Nombre *</label>
                <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                @error('nombre')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="apellidos">Apellidos</label>
                <input id="apellidos" type="text" name="apellidos" value="{{ old('apellidos', $cliente->apellidos) }}">
            </div>
            <div class="field">
                <label for="email">Correo electrónico *</label>
                <input id="email" type="email" name="email" value="{{ old('email', $cliente->user?->email) }}" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="telefono">Teléfono</label>
                <input id="telefono" type="text" name="telefono" value="{{ old('telefono', $cliente->telefono) }}">
            </div>
            <div class="field">
                <label for="ci">Carnet de identidad</label>
                <input id="ci" type="text" name="ci" value="{{ old('ci', $cliente->ci) }}">
            </div>
            <div class="field full">
                <label for="direccion">Dirección</label>
                <input id="direccion" type="text" name="direccion" value="{{ old('direccion', $cliente->direccion) }}">
            </div>
        </div>
        <div class="btn-row" style="margin-top: 20px;">
            <button class="btn btn-primary" type="submit">Guardar cambios</button>
            <a href="{{ route('admin.clientes.show', $cliente) }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
