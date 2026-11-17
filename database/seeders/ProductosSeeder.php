<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Inventario;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Talla;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $poleras = Categoria::where('slug', 'poleras')->firstOrFail()->id;
        $hoodies = Categoria::where('slug', 'hoodies')->firstOrFail()->id;
        $polos = Categoria::where('slug', 'polos')->firstOrFail()->id;

        $productos = [
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera DTF Logo Mostaza',
                'slug' => 'polera-dtf-logo-mostaza',
                'descripcion' => 'Polera urbana de algodón con estampado DTF mostaza. Diseño de alta definición, tacto suave y colores que no se descascarán.',
                'precio' => 89.00,
                'imagen' => 'images/productos/mostaza-logo.svg',
                'imagenes' => ['images/productos/urbana-negra.svg', 'images/productos/minimal-gris.svg'],
                'codigo' => 'DTF-001',
                'destacado' => true,
            ],
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera Urbana Negra',
                'slug' => 'polera-urbana-negra',
                'descripcion' => 'Clásica polera negra con grabado DTF en alta definición. Corte regular, ideal para uso diario y outfit callejero.',
                'precio' => 95.00,
                'imagen' => 'images/productos/urbana-negra.svg',
                'imagenes' => ['images/productos/graffiti-negra.svg', 'images/productos/rayos-azul.svg'],
                'codigo' => 'DTF-002',
                'destacado' => true,
            ],
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera Graffiti Callejero',
                'slug' => 'polera-graffiti-callejero',
                'descripcion' => 'Diseño graffiti con transferencia DTF profesional. Colores intensos y terminación flexible que acompaña cada movimiento.',
                'precio' => 105.00,
                'imagen' => 'images/productos/graffiti-negra.svg',
                'imagenes' => ['images/productos/urbana-blanca.svg', 'images/productos/chaos-roja.svg'],
                'codigo' => 'DTF-003',
                'destacado' => true,
            ],
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera Rayos Eléctricos',
                'slug' => 'polera-rayos-electricos',
                'descripcion' => 'Estampado de rayos eléctricos sobre polera azul. Un diseño llamativo con acabado DTF resistente al lavado.',
                'precio' => 99.00,
                'imagen' => 'images/productos/rayos-azul.svg',
                'imagenes' => ['images/productos/urbana-negra.svg', 'images/productos/mostaza-logo.svg'],
                'codigo' => 'DTF-004',
                'destacado' => false,
            ],
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera Vintage Crema',
                'slug' => 'polera-vintage-crema',
                'descripcion' => 'Tono crema con arte vintage impreso en DTF. Suave, liviana y perfecta para un estilo casual con personalidad.',
                'precio' => 92.00,
                'imagen' => 'images/productos/vintage-crema.svg',
                'imagenes' => ['images/productos/minimal-gris.svg', 'images/productos/urbana-blanca.svg'],
                'codigo' => 'DTF-005',
                'destacado' => false,
            ],
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera Chaos Roja',
                'slug' => 'polera-chaos-roja',
                'descripcion' => 'Diseño caótico en rojo con elementos urbanos. Transferencia DTF de alta adherencia para un look irreverente.',
                'precio' => 110.00,
                'imagen' => 'images/productos/chaos-roja.svg',
                'imagenes' => ['images/productos/graffiti-negra.svg', 'images/productos/urbana-negra.svg'],
                'codigo' => 'DTF-006',
                'destacado' => true,
            ],
            [
                'categoria_id' => $poleras,
                'nombre' => 'Polera Minimal Gris',
                'slug' => 'polera-minimal-gris',
                'descripcion' => 'Diseño minimalista en gris con detalle DTF impecable. Una prenda versátil para combinar en cualquier ocasión.',
                'precio' => 85.00,
                'imagen' => 'images/productos/minimal-gris.svg',
                'imagenes' => ['images/productos/vintage-crema.svg', 'images/productos/mostaza-logo.svg'],
                'codigo' => 'DTF-007',
                'destacado' => false,
            ],
            [
                'categoria_id' => $hoodies,
                'nombre' => 'Hoodie DTF Nocturno',
                'slug' => 'hoodie-dtf-nocturno',
                'descripcion' => 'Hoodie de franela con estampado DTF nocturno. Calidez, comodidad y un diseño que destaca de día y de noche.',
                'precio' => 169.00,
                'imagen' => 'images/productos/urbana-negra.svg',
                'imagenes' => ['images/productos/graffiti-negra.svg', 'images/productos/rayos-azul.svg'],
                'codigo' => 'DTF-008',
                'destacado' => true,
            ],
            [
                'categoria_id' => $polos,
                'nombre' => 'Polo DTF Urban Fit',
                'slug' => 'polo-dtf-urban-fit',
                'descripcion' => 'Polo casual con cuello estructurado y detalle DTF urbano. Ideal para un estilo pulcro sin perder actitud.',
                'precio' => 99.00,
                'imagen' => 'images/productos/urbana-blanca.svg',
                'imagenes' => ['images/productos/minimal-gris.svg', 'images/productos/vintage-crema.svg'],
                'codigo' => 'DTF-009',
                'destacado' => false,
            ],
        ];

        $tallas = Talla::orderBy('orden')->get();
        $colores = Color::orderBy('nombre')->get();
        $contador = 0;

        foreach ($productos as $producto) {
            $registro = Producto::firstOrCreate(
                ['slug' => $producto['slug']],
                [
                    'categoria_id' => $producto['categoria_id'],
                    'nombre' => $producto['nombre'],
                    'descripcion' => $producto['descripcion'],
                    'precio' => $producto['precio'],
                    'imagen' => $producto['imagen'],
                    'imagenes' => $producto['imagenes'],
                    'codigo' => $producto['codigo'],
                    'activo' => true,
                    'destacado' => $producto['destacado'],
                ]
            );

            foreach ($tallas as $talla) {
                foreach ($colores as $color) {
                    $adicional = match ($talla->nombre) {
                        'XL' => 3.00,
                        'XXL' => 6.00,
                        default => 0.00,
                    };

                    $variante = ProductoVariante::firstOrCreate(
                        [
                            'producto_id' => $registro->id,
                            'talla_id' => $talla->id,
                            'color_id' => $color->id,
                        ],
                        ['precio_adicional' => $adicional, 'activo' => true]
                    );

                    $contador++;
                    $stock = $contador <= 2 ? ($contador === 1 ? 3 : 0) : random_int(6, 32);

                    Inventario::firstOrCreate(
                        ['producto_variante_id' => $variante->id],
                        ['stock' => $stock, 'stock_minimo' => 5]
                    );
                }
            }
        }
    }
}
