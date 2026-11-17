@extends('layouts.app')

@section('title', 'Recuperar contraseña | DTF Store 13')

@section('content')
<div class="auth-wrap">
    <div class="container-sm">
        <div class="auth-card">
            <span class="eyebrow">Recuperación</span>
            <h1>¿Olvidaste tu contraseña?</h1>
            <p>Te enviaremos un enlace para restablecerla. En desarrollo local, el enlace queda registrado en el log de Laravel.</p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="field" style="margin-bottom: 18px;">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="padding: 13px;">Enviar enlace</button>
            </form>

            <div class="auth-helper"><a href="{{ route('login') }}">← Volver al inicio de sesión</a></div>
        </div>
    </div>
</div>
@endsection
