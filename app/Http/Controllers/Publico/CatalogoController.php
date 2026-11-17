<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $consulta = Producto::with(['categoria', 'variantes.inventario'])->activos();

        if ($request->filled('categoria')) {
            $categoria = Categoria::where('slug', $request->string('categoria')->toString())->first();
            $categoria ? $consulta->where('categoria_id', $categoria->id) : $consulta->whereRaw('0 = 1');
        }

        if ($request->filled('q')) {
            $busqueda = '%'.$request->string('q')->trim()->toString().'%';
            $consulta->where(function ($subconsulta) use ($busqueda) {
                $subconsulta->where('nombre', 'like', $busqueda)
                    ->orWhere('descripcion', 'like', $busqueda)
                    ->orWhere('codigo', 'like', $busqueda);
            });
        }

        if ($request->boolean('disponibles')) {
            $consulta->whereHas('variantes.inventario', fn ($subconsulta) => $subconsulta->where('stock', '>', 0));
        }

        match ($request->string('orden')->toString()) {
            'precio_asc' => $consulta->orderBy('precio'),
            'precio_desc' => $consulta->orderByDesc('precio'),
            'nombre_desc' => $consulta->orderByDesc('nombre'),
            default => $consulta->orderBy('nombre'),
        };

        $productos = $consulta->paginate(12)->withQueryString();
        $categorias = Categoria::activas()->orderBy('nombre')->get();
        $filtros = $request->only(['categoria', 'q', 'orden', 'disponibles']);

        return view('catalogo.index', compact('productos', 'categorias', 'filtros'));
    }

    public function show(string $slug)
    {
        $producto = Producto::with([
            'categoria',
            'variantes.talla',
            'variantes.color',
            'variantes.inventario',
        ])->activos()->where('slug', $slug)->firstOrFail();

        $relacionados = Producto::with(['categoria', 'variantes.inventario'])
            ->activos()
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->take(4)
            ->get();

        return view('catalogo.show', compact('producto', 'relacionados'));
    }
}
