<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ]);

        $estado = Password::sendResetLink($request->only('email'));

        if ($estado === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Enviamos el enlace de recuperación. Revisa tu correo o el log local de Laravel.');
        }

        return back()->with('error', 'No pudimos enviar el enlace. Verifica el correo e inténtalo nuevamente.');
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Ingresa tu correo electrónico.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $estado = Password::reset(
            $credenciales,
            function ($usuario, string $password) {
                $usuario->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $usuario->save();
                event(new PasswordReset($usuario));
            }
        );

        if ($estado === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Tu contraseña fue actualizada. Inicia sesión para continuar.');
        }

        return back()->with('error', 'El enlace de recuperación no es válido o ya expiró.');
    }
}
