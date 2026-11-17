@extends('layouts.app')

@section('title', 'Iniciar sesión | DTF Store 13')

@section('content')
<div class="auth-wrap">
    <div class="container-sm">
        <div class="auth-card">
            <span class="eyebrow">Bienvenido de nuevo</span>
            <h1>Iniciar sesión</h1>
            <p>Ingresa para revisar tus pedidos y finalizar tus compras.</p>

            <form method="POST" action="{{ route('login.intentar') }}">
                @csrf
                <div class="field" style="margin-bottom: 16px;">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field" style="margin-bottom: 16px;">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <label class="checkbox-line" style="margin-bottom: 18px;">
                    <input type="checkbox" name="remember" value="1"> Recordarme
                </label>
                <button type="submit" class="btn btn-primary btn-block" style="padding: 13px;">Ingresar</button>
            </form>

            <div class="auth-helper">
                <a href="{{ route('password.request') }}" style="font-weight: 700;">¿Olvidaste tu contraseña?</a>
                <p>¿Aún no tienes cuenta? <a href="{{ route('registro') }}" style="color: var(--amarillo); font-weight: 800;">Regístrate</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
