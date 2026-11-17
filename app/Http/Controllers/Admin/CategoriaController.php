<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $consulta = Categoria::withCount('productos');

        if ($request->filled('q')) {
            $busqueda = '%'.$request->string('q')->trim()->toString().'%';
            $consulta->where('nombre', 'like', $busqueda);
        }

        return view('admin.categorias.index', [
            'categorias' => $consulta->orderBy('nombre')->paginate(15)->withQueryString(),
        ]);
    }

    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => $request->string('slug')->trim()->toString() ?: Str::slug($request->string('nombre')->toString()),
        ]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre'],
            'slug' => ['required', 'string', 'max:120', 'unique:categorias,slug'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'activo' => ['sometimes', 'boolean'],
        ], [
            'nombre.required' => 'Ingresa el nombre de la categoría.',
            'nombre.unique' => 'Esa categoría ya existe.',
        ]);

        Categoria::create([
            'nombre' => $datos['nombre'],
            'slug' => $datos['slug'],
            'descripcion' => $datos['descripcion'] ?? null,
            'activo' => $request->has('activo') ? $request->boolean('activo') : true,
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    public function show(Categoria $categoria)
    {
        return view('admin.categorias.show', [
            'categoria' => $categoria->load('productos'),
        ]);
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $request->merge([
            'slug' => $request->string('slug')->trim()->toString() ?: Str::slug($request->string('nombre')->toString()),
        ]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias,nombre,'.$categoria->id],
            'slug' => ['required', 'string', 'max:120', 'unique:categorias,slug,'.$categoria->id],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $categoria->update([
            'nombre' => $datos['nombre'],
            'slug' => $datos['slug'],
            'descripcion' => $datos['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->productos()->exists()) {
            return back()->with('error', 'No se puede eliminar una categoría que tiene productos asociados.');
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
