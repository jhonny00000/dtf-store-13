<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\Talla;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductoRegistroTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_valido_de_un_producto(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $categoria = Categoria::create(['nombre' => 'Poleras', 'slug' => 'poleras']);
        $talla = Talla::create(['nombre' => 'M', 'orden' => 2]);
        $color = Color::create(['nombre' => 'Negro', 'codigo' => '#111111']);

        $respuesta = $this->actingAs($admin)->post(route('admin.productos.store'), [
            'categoria_id' => $categoria->id,
            'nombre' => 'Polera Prueba DTF',
            'descripcion' => 'Polera de prueba con descripción suficiente para la validación.',
            'precio' => 99.50,
            'codigo' => 'TEST-001',
            'tallas' => [$talla->id],
            'colores' => [$color->id],
            'stock' => 10,
            'stock_minimo' => 2,
            'activo' => 1,
            'destacado' => 1,
        ]);

        $respuesta->assertRedirect();

        $producto = Producto::where('slug', 'polera-prueba-dtf')->firstOrFail();

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Polera Prueba DTF',
            'activo' => 1,
            'destacado' => 1,
        ]);
        $this->assertDatabaseHas('producto_variantes', [
            'producto_id' => $producto->id,
            'talla_id' => $talla->id,
            'color_id' => $color->id,
        ]);
        $this->assertDatabaseHas('inventarios', [
            'producto_variante_id' => $producto->variantes()->firstOrFail()->id,
            'stock' => 10,
            'stock_minimo' => 2,
        ]);
        $this->assertSame(10, Inventario::firstOrFail()->stock);
    }
}
