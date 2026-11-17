@extends('layouts.app')

@section('title', 'Registro | DTF Store 13')

@section('content')
<div class="auth-wrap">
    <div class="container-sm">
        <div class="auth-card">
            <span class="eyebrow">Únete a la comunidad</span>
            <h1>Crear cuenta</h1>
            <p>Regístrate para comprar y seguir el estado de tus pedidos.</p>

            <form method="POST" action="{{ route('registro.guardar') }}">
                @csrf
                <div class="field" style="margin-bottom: 14px;">
                    <label for="name">Nombre completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field" style="margin-bottom: 14px;">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password">
                        @error('password')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                <div class="form-grid" style="margin-top: 14px;">
                    <div class="field">
                        <label for="telefono">Teléfono (opcional)</label>
                        <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    </div>
                    <div class="field">
                        <label for="direccion">Dirección (opcional)</label>
                        <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px; padding: 13px;">Crear cuenta</button>
            </form>

            <div class="auth-helper">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" style="color: var(--amarillo); font-weight: 800;">Inicia sesión</a>
            </div>
        </div>
    </div>
</div>
@endsection
