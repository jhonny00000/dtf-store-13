<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;

class HomeController extends Controller
{
    public function index()
    {
        $destacados = Producto::with(['categoria', 'variantes.inventario'])
            ->activos()
            ->destacados()
            ->latest()
            ->take(8)
            ->get();

        $categorias = Categoria::activas()
            ->withCount(['productos' => fn ($consulta) => $consulta->where('activo', true)])
            ->orderBy('nombre')
            ->get();

        $novedades = Producto::with(['categoria', 'variantes.inventario'])
            ->activos()
            ->latest()
            ->take(4)
            ->get();

        return view('home.index', compact('destacados', 'categorias', 'novedades'));
    }
}
