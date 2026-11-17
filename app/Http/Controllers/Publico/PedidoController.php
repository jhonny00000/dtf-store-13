<?php

namespace App\Http\Controllers\Publico;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\Pago;
use App\Models\Pedido;
use App\Services\CarritoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PedidoController extends Controller
{
    public function __construct(private CarritoService $carrito)
    {
    }

    public function checkout()
    {
        $items = $this->carrito->items();

        if ($items === []) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        return view('pedidos.checkout', [
            'items' => $items,
            'subtotal' => $this->carrito->subtotal(),
            'costoEnvio' => $this->carrito->costoEnvio(),
            'total' => $this->carrito->total(),
            'metodos' => Pedido::$metodosPago,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($this->carrito->items() === []) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $datos = $request->validate([
            'direccion_entrega' => ['required', 'string', 'min:8', 'max:500'],
            'metodo_pago' => ['required', Rule::in(array_keys(Pedido::$metodosPago))],
            'notas' => ['nullable', 'string', 'max:500'],
        ], [
            'direccion_entrega.required' => 'Ingresa la dirección de entrega.',
            'direccion_entrega.min' => 'La dirección debe tener al menos 8 caracteres.',
            'metodo_pago.required' => 'Selecciona un método de pago.',
        ]);

        $pedido = DB::transaction(function () use ($datos) {
            $items = $this->carrito->items();
            $usuario = auth()->user();

            $cliente = $usuario->cliente ?: Cliente::create([
                'user_id' => $usuario->id,
                'nombre' => $usuario->name,
                'telefono' => $usuario->telefono,
            ]);

            $inventarios = Inventario::whereIn('producto_variante_id', array_keys($items))
                ->lockForUpdate()
                ->get()
                ->keyBy('producto_variante_id');

            foreach ($items as $id => $item) {
                $inventario = $inventarios->get($id);

                if (! $inventario || $inventario->stock < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'carrito' => 'Uno de los productos ya no tiene stock suficiente. Revisa tu carrito e inténtalo nuevamente.',
                    ]);
                }
            }

            $subtotal = $this->carrito->subtotal();
            $costoEnvio = $this->carrito->costoEnvio();

            $pedido = Pedido::create([
                'numero' => $this->generarNumero(),
                'user_id' => $usuario->id,
                'cliente_id' => $cliente->id,
                'direccion_entrega' => $datos['direccion_entrega'],
                'metodo_pago' => $datos['metodo_pago'],
                'estado' => 'pendiente',
                'subtotal' => $subtotal,
                'costo_envio' => $costoEnvio,
                'total' => $subtotal + $costoEnvio,
                'notas' => $datos['notas'] ?? null,
                'fecha_pedido' => now(),
            ]);

            foreach ($items as $id => $item) {
                $variante = $item['variante'];
                $inventario = $inventarios->get($id);
                $stockAnterior = $inventario->stock;

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $variante->producto_id,
                    'producto_variante_id' => $variante->id,
                    'nombre_producto' => $variante->producto->nombre,
                    'talla_nombre' => $variante->talla?->nombre ?? 'Única',
                    'color_nombre' => $variante->color?->nombre ?? 'Único',
                    'imagen' => $variante->producto->imagen,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $item['subtotal'],
                ]);

                $actualizado = Inventario::where('id', $inventario->id)
                    ->where('stock', '>=', $item['cantidad'])
                    ->decrement('stock', $item['cantidad']);

                if (! $actualizado) {
                    throw ValidationException::withMessages([
                        'carrito' => 'No fue posible reservar el stock de tu pedido. Inténtalo nuevamente.',
                    ]);
                }

                MovimientoInventario::create([
                    'inventario_id' => $inventario->id,
                    'user_id' => $usuario->id,
                    'tipo' => 'venta',
                    'cantidad' => $item['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $stockAnterior - $item['cantidad'],
                    'motivo' => 'Venta registrada',
                    'referencia' => $pedido->numero,
                ]);
            }

            Pago::create([
                'pedido_id' => $pedido->id,
                'metodo' => $datos['metodo_pago'],
                'estado' => 'pendiente',
                'monto' => $pedido->total,
            ]);

            return $pedido;
        });

        $this->carrito->vaciar();

        return redirect()->route('pedidos.exitoso', $pedido)
            ->with('success', '¡Pedido registrado correctamente!');
    }

    public function exitoso(Pedido $pedido)
    {
        $this->verificarPropietario($pedido);

        return view('pedidos.exitoso', ['pedido' => $pedido->load('detalles', 'pago')]);
    }

    public function index()
    {
        $pedidos = auth()->user()->pedidos()
            ->with('detalles')
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $this->verificarPropietario($pedido);

        return view('pedidos.show', ['pedido' => $pedido->load('detalles', 'pago')]);
    }

    private function verificarPropietario(Pedido $pedido): void
    {
        abort_unless($pedido->user_id === auth()->id(), 404);
    }

    private function generarNumero(): string
    {
        $prefijo = 'DTF-'.now()->format('Ymd').'-';
        $numero = $prefijo.str_pad((string) (Pedido::count() + 1), 5, '0', STR_PAD_LEFT);

        while (Pedido::where('numero', $numero)->exists()) {
            $numero = $prefijo.strtoupper(Str::random(5));
        }

        return $numero;
    }
}
