<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeguridadTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_no_autenticado_no_puede_acceder_al_panel_administrativo(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.productos.index'))->assertRedirect(route('login'));
    }

    public function test_usuario_no_autenticado_no_puede_registrar_productos(): void
    {
        $respuesta = $this->post(route('admin.productos.store'), [
            'nombre' => 'Producto sin autorización',
        ]);

        $respuesta->assertRedirect(route('login'));
        $this->assertDatabaseCount('productos', 0);
    }

    public function test_cliente_autenticado_no_puede_acceder_al_panel(): void
    {
        $cliente = User::factory()->create(['role' => 'cliente']);

        $this->actingAs($cliente)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));

        $this->actingAs($cliente)
            ->post(route('admin.productos.store'), ['nombre' => 'Intento de producto'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('productos', 0);
    }
}
