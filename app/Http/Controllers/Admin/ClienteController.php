<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $consulta = Cliente::with('user');

        if ($request->filled('q')) {
            $busqueda = '%'.$request->string('q')->trim()->toString().'%';
            $consulta->where(function ($subconsulta) use ($busqueda) {
                $subconsulta->where('nombre', 'like', $busqueda)
                    ->orWhere('telefono', 'like', $busqueda)
                    ->orWhereHas('user', fn ($consultaUsuario) => $consultaUsuario->where('email', 'like', $busqueda));
            });
        }

        return view('admin.clientes.index', [
            'clientes' => $consulta->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($datos) {
            $usuario = User::create([
                'name' => $datos['name'],
                'email' => $datos['email'],
                'password' => Hash::make($datos['password']),
                'role' => 'cliente',
                'telefono' => $datos['telefono'] ?? null,
            ]);

            Cliente::create([
                'user_id' => $usuario->id,
                'nombre' => $datos['name'],
                'telefono' => $datos['telefono'] ?? null,
                'direccion' => $datos['direccion'] ?? null,
            ]);
        });

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        return view('admin.clientes.show', [
            'cliente' => $cliente->load('user', 'pedidos.detalles'),
        ]);
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', ['cliente' => $cliente->load('user')]);
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'apellidos' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.($cliente->user_id ?? 0)],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ci' => ['nullable', 'string', 'max:30'],
        ]);

        DB::transaction(function () use ($datos, $cliente) {
            $cliente->update([
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'] ?? null,
                'telefono' => $datos['telefono'] ?? null,
                'direccion' => $datos['direccion'] ?? null,
                'ci' => $datos['ci'] ?? null,
            ]);

            if ($cliente->user) {
                $cliente->user->update([
                    'name' => $datos['nombre'],
                    'email' => $datos['email'],
                    'telefono' => $datos['telefono'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.clientes.show', $cliente)
            ->with('success', 'Cliente actualizado correctamente.');
    }
}
