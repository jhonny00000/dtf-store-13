<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\ProductoVariante;
use App\Services\CarritoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function __construct(private CarritoService $carrito)
    {
    }

    public function index()
    {
        return view('carrito.index', [
            'items' => $this->carrito->items(),
            'subtotal' => $this->carrito->subtotal(),
            'costoEnvio' => $this->carrito->costoEnvio(),
            'total' => $this->carrito->total(),
        ]);
    }

    public function agregar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'variante_id' => ['required', 'integer', 'exists:producto_variantes,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $this->carrito->agregar((int) $datos['variante_id'], (int) $datos['cantidad']);

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, ProductoVariante $variante): RedirectResponse
    {
        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:0'],
        ]);

        $this->carrito->actualizar($variante->id, (int) $datos['cantidad']);

        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado.');
    }

    public function eliminar(ProductoVariante $variante): RedirectResponse
    {
        $this->carrito->eliminar($variante->id);

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar(): RedirectResponse
    {
        $this->carrito->vaciar();

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }
}
