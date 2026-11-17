<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Talla;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DatosInvalidosTest extends TestCase
{
    use RefreshDatabase;

    public function test_rechaza_campos_obligatorios_vacios_valores_negativos_y_datos_incorrectos(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $respuesta = $this->actingAs($admin)->post(route('admin.productos.store'), [
            'categoria_id' => '',
            'nombre' => '',
            'descripcion' => '',
            'precio' => -5,
            'tallas' => [],
            'colores' => [],
            'stock' => -1,
        ]);

        $respuesta->assertSessionHasErrors([
            'categoria_id',
            'nombre',
            'descripcion',
            'precio',
            'tallas',
            'colores',
            'stock',
        ]);

        $this->assertDatabaseCount('productos', 0);
        $this->assertDatabaseCount('producto_variantes', 0);
    }

    public function test_rechaza_categoria_inexistente_y_no_guarda_el_producto(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $talla = Talla::create(['nombre' => 'S', 'orden' => 1]);
        $color = Color::create(['nombre' => 'Blanco', 'codigo' => '#FFFFFF']);

        $respuesta = $this->actingAs($admin)->post(route('admin.productos.store'), [
            'categoria_id' => 99999,
            'nombre' => 'Producto inválido',
            'descripcion' => 'Descripción válida con longitud suficiente.',
            'precio' => 50,
            'tallas' => [$talla->id],
            'colores' => [$color->id],
            'stock' => 5,
        ]);

        $respuesta->assertSessionHasErrors('categoria_id');
        $this->assertDatabaseCount('productos', 0);
    }

    public function test_rechaza_archivo_que_no_es_imagen(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $categoria = Categoria::create(['nombre' => 'Poleras', 'slug' => 'poleras']);
        $talla = Talla::create(['nombre' => 'M', 'orden' => 2]);
        $color = Color::create(['nombre' => 'Negro', 'codigo' => '#111111']);

        $respuesta = $this->actingAs($admin)->post(route('admin.productos.store'), [
            'categoria_id' => $categoria->id,
            'nombre' => 'Producto con imagen inválida',
            'descripcion' => 'Descripción válida con longitud suficiente.',
            'precio' => 50,
            'tallas' => [$talla->id],
            'colores' => [$color->id],
            'stock' => 5,
            'imagen' => UploadedFile::fake()->create('documento.txt', 10, 'text/plain'),
        ]);

        $respuesta->assertSessionHasErrors('imagen');
        $this->assertDatabaseCount('productos', 0);
    }
}
