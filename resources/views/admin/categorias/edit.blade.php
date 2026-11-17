@extends('layouts.admin')

@section('title', 'Editar categoría')

@section('content')
<p class="admin-subtitle">Actualiza los datos de la categoría {{ $categoria->nombre }}.</p>
<div class="panel container-sm" style="width: min(680px, 100%);">
    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
        @csrf
        @method('PUT')
        <div class="field" style="margin-bottom: 14px;">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required>
            @error('nombre')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom: 14px;">
            <label for="slug">Slug (opcional)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $categoria->slug) }}">
            @error('slug')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom: 14px;">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion">{{ old('descripcion', $categoria->descripcion) }}</textarea>
        </div>
        <label class="checkbox-line" style="margin-bottom: 20px;"><input type="checkbox" name="activo" value="1" @checked(old('activo', $categoria->activo))> Categoría activa</label>
        <div class="btn-row">
            <button class="btn btn-primary" type="submit">Guardar cambios</button>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
