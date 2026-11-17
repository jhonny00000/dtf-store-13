@php
    $esEdicion = isset($producto) && $producto->exists;
    $tallasSeleccionadas = $esEdicion ? $producto->variantes->pluck('talla_id')->all() : [];
    $coloresSeleccionados = $esEdicion ? $producto->variantes->pluck('color_id')->all() : [];
@endphp

<form method="POST" action="{{ $esEdicion ? route('admin.productos.update', $producto) : route('admin.productos.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($esEdicion) @method('PUT') @endif

    <div class="form-grid">
        <div class="field">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}" required>
            @error('nombre')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="slug">Slug (opcional)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $producto->slug ?? '') }}" placeholder="Se genera automáticamente">
            @error('slug')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="categoria_id">Categoría *</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id', $producto->categoria_id ?? '') == $categoria->id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_id')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="codigo">Código / SKU (opcional)</label>
            <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $producto->codigo ?? '') }}">
            @error('codigo')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="precio">Precio en Bs *</label>
            <input type="number" id="precio" name="precio" step="0.01" min="0.01" value="{{ old('precio', $producto->precio ?? '') }}" required>
            @error('precio')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="stock">Stock total *</label>
            <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $esEdicion ? $producto->stock_total : 0) }}" required>
            @error('stock')<span class="field-error">{{ $message }}</span>@enderror
            @if ($esEdicion)<small style="color: var(--texto-suave);">El stock real se administra por variante en el módulo de inventario.</small>@endif
        </div>
        <div class="field">
            <label for="stock_minimo">Stock mínimo por variante</label>
            <input type="number" id="stock_minimo" name="stock_minimo" min="0" value="{{ old('stock_minimo', 5) }}">
            @error('stock_minimo')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field full">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" required>{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
            @error('descripcion')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label for="imagen">Imagen principal</label>
            <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp" data-preview="preview-imagen">
            @error('imagen')<span class="field-error">{{ $message }}</span>@enderror
            @if ($esEdicion && $producto->imagen)
                <img id="preview-imagen" class="img-preview" src="{{ asset($producto->imagen) }}" alt="Vista previa">
            @else
                <img id="preview-imagen" class="img-preview" src="" alt="Vista previa" style="display: none;">
            @endif
        </div>
        <div class="field">
            <label for="imagenes">Galería (máximo 4 imágenes)</label>
            <input type="file" id="imagenes" name="imagenes[]" accept="image/jpeg,image/png,image/webp" multiple>
            @error('imagenes.*')<span class="field-error">{{ $message }}</span>@enderror
            @if ($esEdicion && $producto->imagenes)
                <div class="miniaturas">
                    @foreach ($producto->imagenes as $imagen)
                        <img src="{{ asset($imagen) }}" class="thumb-table" alt="Galería" style="width: 62px; height: 62px; object-fit: cover; border-radius: 8px;">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="field">
            <label>Tallas disponibles *</label>
            <div class="opciones">
                @foreach ($tallas as $talla)
                    <div class="opcion">
                        <input type="checkbox" id="talla-{{ $talla->id }}" name="tallas[]" value="{{ $talla->id }}" @checked(in_array($talla->id, old('tallas', $tallasSeleccionadas)))>
                        <label for="talla-{{ $talla->id }}">{{ $talla->nombre }}</label>
                    </div>
                @endforeach
            </div>
            @error('tallas')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label>Colores disponibles *</label>
            <div class="opciones">
                @foreach ($colores as $color)
                    <div class="opcion">
                        <input type="checkbox" id="color-{{ $color->id }}" name="colores[]" value="{{ $color->id }}" @checked(in_array($color->id, old('colores', $coloresSeleccionados)))>
                        <label for="color-{{ $color->id }}"><span class="color-dot" style="background: {{ $color->codigo }}"></span>{{ $color->nombre }}</label>
                    </div>
                @endforeach
            </div>
            @error('colores')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        <div class="field full">
            <label class="checkbox-line"><input type="checkbox" name="activo" value="1" @checked(old('activo', $esEdicion ? $producto->activo : true))> Producto activo</label>
            <label class="checkbox-line" style="margin-top: 8px;"><input type="checkbox" name="destacado" value="1" @checked(old('destacado', $producto->destacado ?? false))> Mostrar en destacados</label>
        </div>
    </div>

    <div class="btn-row" style="margin-top: 24px;">
        <button type="submit" class="btn btn-primary">{{ $esEdicion ? 'Guardar cambios' : 'Registrar producto' }}</button>
        <a href="{{ route('admin.productos.index') }}" class="btn btn-outline">Cancelar</a>
    </div>
</form>
