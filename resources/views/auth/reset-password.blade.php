@extends('layouts.app')

@section('title', 'Restablecer contraseña | DTF Store 13')

@section('content')
<div class="auth-wrap">
    <div class="container-sm">
        <div class="auth-card">
            <span class="eyebrow">Nueva contraseña</span>
            <h1>Restablecer contraseña</h1>
            <p>Define una contraseña nueva de al menos 8 caracteres.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="field" style="margin-bottom: 14px;">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field" style="margin-bottom: 14px;">
                    <label for="password">Nueva contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field" style="margin-bottom: 18px;">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="padding: 13px;">Actualizar contraseña</button>
            </form>
        </div>
    </div>
</div>
@endsection
