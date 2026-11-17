@extends('layouts.admin')

@section('title', 'Registrar cliente')

@section('content')
<p class="admin-subtitle">El cliente podrá iniciar sesión con el correo y la contraseña indicados.</p>
<div class="panel container-sm" style="width: min(680px, 100%);">
    <form method="POST" action="{{ route('admin.clientes.store') }}">
        @csrf
        <div class="form-grid">
            <div class="field">
                <label for="name">Nombre *</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="email">Correo electrónico *</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="password">Contraseña *</label>
                <input id="password" type="password" name="password" minlength="8" required>
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="telefono">Teléfono</label>
                <input id="telefono" type="text" name="telefono" value="{{ old('telefono') }}">
            </div>
            <div class="field full">
                <label for="direccion">Dirección</label>
                <input id="direccion" type="text" name="direccion" value="{{ old('direccion') }}">
            </div>
        </div>
        <div class="btn-row" style="margin-top: 20px;">
            <button class="btn btn-primary" type="submit">Registrar cliente</button>
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline">Cancelar</a>
        </div>
    </form>
</div>
@endsection
