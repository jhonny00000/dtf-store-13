@extends('layouts.admin')

@section('title', 'Nueva categoría')

@section('content')
<p class="admin-subtitle">Crea una nueva línea de productos para la tienda.</p>
<div class="panel container-sm" style="width: min(680px, 100%);">
    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf
        <div class="field" style="margin-bottom: 14px;">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            @error('nombre')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom: 14px;">
            <label for="slug">Slug (opcional)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug') }}">
            @error('slug')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field" style="margin-bottom: 14px;">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
        </div>
        <label class="checkbox-line" style="margin-bottom: 20px;"><input type="checkbox" name="activo" value="1" @checked(old('activo', true))> Categoría activa</label>
        <div class="btn-row">
            <button class="btn btn-primary" type="submit">Registrar categoría</button>
            <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
