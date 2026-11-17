<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RutasYVistasTest extends TestCase
{
    use RefreshDatabase;

    public function test_flujo_de_compra_y_vistas_principales_funcionan(): void
    {
        $this->seed();

        $producto = Producto::firstOrFail();
        $variante = $producto->variantes()
            ->whereHas('inventario', fn ($consulta) => $consulta->where('stock', '>', 0))
            ->firstOrFail();
        $stockInicial = $variante->inventario->stock;

        $this->get(route('home'))->assertOk();
        $this->get(route('catalogo.index'))->assertOk();
        $this->get(route('catalogo.show', $producto))->assertOk();
        $this->get(route('carrito.index'))->assertOk();
        $this->get(route('login'))->assertOk();
        $this->get(route('registro'))->assertOk();
        $this->get(route('password.request'))->assertOk();

        $usuario = User::factory()->create(['role' => 'cliente']);
        Cliente::create([
            'user_id' => $usuario->id,
            'nombre' => $usuario->name,
            'direccion' => 'Calle 13 Nro. 13, Zona Central',
        ]);

        $this->actingAs($usuario)
            ->post(route('carrito.agregar'), ['variante_id' => $variante->id, 'cantidad' => 1])
            ->assertRedirect(route('carrito.index'));

        $this->get(route('carrito.index'))->assertOk();
        $this->get(route('pedidos.checkout'))->assertOk();

        $this->post(route('pedidos.store'), [
            'direccion_entrega' => 'Calle 13 Nro. 13, Zona Central',
            'metodo_pago' => 'efectivo',
        ])->assertRedirect();

        $pedido = Pedido::firstOrFail();

        $this->assertSame(1, $pedido->detalles->count());
        $this->assertSame($producto->nombre, $pedido->detalles->first()->nombre_producto);
        $this->assertSame($stockInicial - 1, $variante->inventario->fresh()->stock);

        $this->get(route('pedidos.exitoso', $pedido))->assertOk();
        $this->get(route('pedidos.index'))->assertOk();
        $this->get(route('pedidos.show', $pedido))->assertOk();

        $admin = User::where('role', 'admin')->firstOrFail();
        $categoria = $producto->categoria;
        $cliente = Cliente::firstOrFail();

        $this->actingAs($admin);

        foreach ([
            route('admin.dashboard'),
            route('admin.productos.index'),
            route('admin.productos.create'),
            route('admin.productos.show', $producto),
            route('admin.productos.edit', $producto),
            route('admin.categorias.index'),
            route('admin.categorias.create'),
            route('admin.categorias.show', $categoria),
            route('admin.categorias.edit', $categoria),
            route('admin.inventario.index'),
            route('admin.inventario.movimientos'),
            route('admin.inventario.edit', $variante),
            route('admin.clientes.index'),
            route('admin.clientes.create'),
            route('admin.clientes.show', $cliente),
            route('admin.clientes.edit', $cliente),
            route('admin.pedidos.index'),
            route('admin.pedidos.show', $pedido),
            route('admin.pedidos.edit', $pedido),
            route('admin.reportes.index'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
