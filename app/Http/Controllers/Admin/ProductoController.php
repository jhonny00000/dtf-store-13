<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Color;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\ProductoVariante;
use App\Models\Talla;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $consulta = Producto::with('categoria')->withCount('variantes');

        if ($request->filled('q')) {
            $busqueda = '%'.$request->string('q')->trim()->toString().'%';
            $consulta->where(function ($subconsulta) use ($busqueda) {
                $subconsulta->where('nombre', 'like', $busqueda)->orWhere('codigo', 'like', $busqueda);
            });
        }

        if ($request->filled('categoria')) {
            $consulta->where('categoria_id', $request->integer('categoria'));
        }

        $productos = $consulta->latest()->paginate(15)->withQueryString();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        return view('admin.productos.create', [
            'categorias' => Categoria::activas()->orderBy('nombre')->get(),
            'tallas' => Talla::ordenadas()->get(),
            'colores' => Color::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'slug' => $request->string('slug')->trim()->toString() ?: Str::slug($request->string('nombre')->toString()),
        ]);

        $datos = $this->validar($request);
        $imagenPrincipal = $request->hasFile('imagen')
            ? $this->subirImagen($request->file('imagen'))
            : 'images/productos/default.svg';

        $galeria = [];

        foreach ($request->file('imagenes', []) as $archivo) {
            $galeria[] = $this->subirImagen($archivo);
        }

        $activo = $request->has('activo') ? $request->boolean('activo') : true;
        $destacado = $request->boolean('destacado');

        $producto = DB::transaction(function () use ($datos, $imagenPrincipal, $galeria, $activo, $destacado) {
            $producto = Producto::create([
                'categoria_id' => $datos['categoria_id'],
                'nombre' => $datos['nombre'],
                'slug' => $datos['slug'],
                'descripcion' => $datos['descripcion'],
                'precio' => $datos['precio'],
                'imagen' => $imagenPrincipal,
                'imagenes' => $galeria,
                'codigo' => $datos['codigo'] ?? null,
                'activo' => $activo,
                'destacado' => $destacado,
            ]);

            $this->sincronizarVariantes(
                $producto,
                $datos['tallas'],
                $datos['colores'],
                (int) $datos['stock'],
                (int) ($datos['stock_minimo'] ?? 5),
                true
            );

            return $producto;
        });

        return redirect()->route('admin.productos.show', $producto)
            ->with('success', 'Producto registrado correctamente.');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'variantes.talla', 'variantes.color', 'variantes.inventario']);

        return view('admin.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', [
            'producto' => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'tallas' => Talla::ordenadas()->get(),
            'colores' => Color::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $request->merge([
            'slug' => $request->string('slug')->trim()->toString() ?: Str::slug($request->string('nombre')->toString()),
        ]);

        $datos = $this->validar($request, $producto->id);

        $imagenPrincipal = $request->hasFile('imagen')
            ? $this->subirImagen($request->file('imagen'))
            : $producto->imagen;

        $galeria = $request->hasFile('imagenes')
            ? collect($request->file('imagenes'))->map(fn (UploadedFile $archivo) => $this->subirImagen($archivo))->all()
            : ($producto->imagenes ?? []);

        $activo = $request->boolean('activo');
        $destacado = $request->boolean('destacado');

        DB::transaction(function () use ($datos, $producto, $imagenPrincipal, $galeria, $activo, $destacado) {
            $producto->update([
                'categoria_id' => $datos['categoria_id'],
                'nombre' => $datos['nombre'],
                'slug' => $datos['slug'],
                'descripcion' => $datos['descripcion'],
                'precio' => $datos['precio'],
                'imagen' => $imagenPrincipal,
                'imagenes' => $galeria,
                'codigo' => $datos['codigo'] ?? null,
                'activo' => $activo,
                'destacado' => $destacado,
            ]);

            $this->sincronizarVariantes($producto, $datos['tallas'], $datos['colores']);
        });

        return redirect()->route('admin.productos.show', $producto)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function cambiarEstado(Producto $producto): RedirectResponse
    {
        $producto->update(['activo' => ! $producto->activo]);

        return back()->with('success', $producto->activo ? 'Producto activado.' : 'Producto desactivado.');
    }

    private function validar(Request $request, ?int $productoId = null): array
    {
        return $request->validate([
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:170', 'unique:productos,slug'.($productoId ? ','.$productoId : '')],
            'descripcion' => ['required', 'string', 'min:10'],
            'precio' => ['required', 'numeric', 'min:0.01'],
            'codigo' => ['nullable', 'string', 'max:50', 'unique:productos,codigo'.($productoId ? ','.$productoId : '')],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'imagenes' => ['nullable', 'array', 'max:4'],
            'imagenes.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'tallas' => ['required', 'array', 'min:1'],
            'tallas.*' => ['integer', 'exists:tallas,id'],
            'colores' => ['required', 'array', 'min:1'],
            'colores.*' => ['integer', 'exists:colores,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['nullable', 'integer', 'min:0'],
            'activo' => ['sometimes', 'boolean'],
            'destacado' => ['sometimes', 'boolean'],
        ], [
            'categoria_id.required' => 'Selecciona una categoría.',
            'nombre.required' => 'Ingresa el nombre del producto.',
            'descripcion.required' => 'Ingresa una descripción.',
            'precio.required' => 'Ingresa el precio.',
            'precio.min' => 'El precio debe ser mayor que 0.',
            'tallas.required' => 'Selecciona al menos una talla.',
            'colores.required' => 'Selecciona al menos un color.',
            'stock.required' => 'Ingresa el stock inicial.',
            'stock.min' => 'El stock no puede ser negativo.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagenes.*.image' => 'Todos los archivos de la galería deben ser imágenes.',
        ]);
    }

    private function sincronizarVariantes(
        Producto $producto,
        array $tallas,
        array $colores,
        int $stock = 0,
        int $stockMinimo = 5,
        bool $inicializar = false
    ): void {
        $ids = [];

        foreach ($tallas as $talla) {
            foreach ($colores as $color) {
                $adicional = Talla::find($talla)?->nombre === 'XXL' ? 6.00 : (Talla::find($talla)?->nombre === 'XL' ? 3.00 : 0.00);

                $variante = ProductoVariante::firstOrCreate(
                    [
                        'producto_id' => $producto->id,
                        'talla_id' => $talla,
                        'color_id' => $color,
                    ],
                    ['precio_adicional' => $adicional, 'activo' => true]
                );

                $variante->update(['precio_adicional' => $adicional, 'activo' => true]);
                $variante->inventario()->firstOrCreate([], [
                    'stock' => 0,
                    'stock_minimo' => $stockMinimo,
                ]);
                $ids[] = $variante->id;
            }
        }

        ProductoVariante::where('producto_id', $producto->id)->whereNotIn('id', $ids)->delete();

        if (! $inicializar) {
            return;
        }

        $variantes = $producto->variantes()->with('inventario')->get();
        $cantidad = $variantes->count();

        if ($cantidad === 0) {
            return;
        }

        $base = intdiv($stock, $cantidad);
        $resto = $stock % $cantidad;

        foreach ($variantes as $indice => $variante) {
            $stockVariante = $base + ($indice < $resto ? 1 : 0);
            $variante->inventario->update([
                'stock' => $stockVariante,
                'stock_minimo' => $stockMinimo,
            ]);

            if ($stockVariante > 0) {
                MovimientoInventario::create([
                    'inventario_id' => $variante->inventario->id,
                    'user_id' => auth()->id(),
                    'tipo' => 'entrada',
                    'cantidad' => $stockVariante,
                    'stock_anterior' => 0,
                    'stock_nuevo' => $stockVariante,
                    'motivo' => 'Stock inicial del producto',
                    'referencia' => $producto->codigo ?? $producto->slug,
                ]);
            }
        }
    }

    private function subirImagen(UploadedFile $archivo): string
    {
        File::ensureDirectoryExists(public_path('images/productos'));
        $nombre = Str::random(24).'.'.$archivo->getClientOriginalExtension();
        $archivo->move(public_path('images/productos'), $nombre);

        return 'images/productos/'.$nombre;
    }
}
