<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Color;
use App\Models\Talla;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Poleras', 'descripcion' => 'Poleras urbanas con estampado DTF de alta resistencia.'],
            ['nombre' => 'Hoodies', 'descripcion' => 'Hoodies cómodos con diseños exclusivos DTF.'],
            ['nombre' => 'Polos', 'descripcion' => 'Polos casuales con acabados profesionales.'],
            ['nombre' => 'Accesorios', 'descripcion' => 'Complementos con estampado personalizado.'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['slug' => Str::slug($categoria['nombre'])],
                ['nombre' => $categoria['nombre'], 'descripcion' => $categoria['descripcion'], 'activo' => true]
            );
        }

        foreach (['S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5] as $nombre => $orden) {
            Talla::firstOrCreate(['nombre' => $nombre], ['orden' => $orden]);
        }

        foreach ([['Negro', '#111111'], ['Blanco', '#F5F5F0'], ['Mostaza', '#D4A017'], ['Azul', '#1F4E79']] as $color) {
            Color::firstOrCreate(['nombre' => $color[0]], ['codigo' => $color[1]]);
        }
    }
}
