<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(
                Auth::user()->isAdmin() ? route('admin.dashboard') : route('home')
            )->with('success', '¡Bienvenido a DTF Store 13!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son correctas.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Ingresa tu nombre.',
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.unique' => 'Ese correo ya está registrado.',
            'password.required' => 'Ingresa una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => $datos['password'],
                'role' => 'cliente',
                'telefono' => $datos['telefono'] ?? null,
            ]);

            Cliente::create([
                'user_id' => $usuario->id,
                'nombre' => $datos['name'],
                'telefono' => $datos['telefono'] ?? null,
                'direccion' => $datos['direccion'] ?? null,
            ]);

            return $usuario;
        });

        Auth::login($usuario);

        return redirect()->route('home')->with('success', 'Tu cuenta fue creada correctamente. ¡Ya puedes comprar!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Cerraste sesión correctamente.');
    }
}
