<?php

namespace App\Services;

use App\Models\ProductoVariante;
use Illuminate\Validation\ValidationException;

class CarritoService
{
    private const CLAVE = 'carrito_dtf13';
    private const COSTO_ENVIO = 15.00;
    private const ENVIO_GRATIS = 300.00;

    public function agregar(int $varianteId, int $cantidad): void
    {
        $variante = $this->buscarVariante($varianteId);
        $stock = $variante->inventario?->stock ?? 0;
        $cantidades = $this->cantidades();
        $yaAgregado = (int) ($cantidades[$varianteId] ?? 0);

        if ($cantidad < 1) {
            throw ValidationException::withMessages(['cantidad' => 'La cantidad debe ser al menos 1.']);
        }

        if ($stock < 1) {
            throw ValidationException::withMessages(['cantidad' => 'Esta variante no está disponible en este momento.']);
        }

        if ($yaAgregado + $cantidad > $stock) {
            throw ValidationException::withMessages([
                'cantidad' => 'Stock insuficiente. Ya tienes '.$yaAgregado.' unidad(es) en el carrito y solo quedan '.$stock.'.',
            ]);
        }

        $cantidades[$varianteId] = $yaAgregado + $cantidad;
        $this->guardar($cantidades);
    }

    public function actualizar(int $varianteId, int $cantidad): void
    {
        $variante = $this->buscarVariante($varianteId);
        $stock = $variante->inventario?->stock ?? 0;
        $cantidades = $this->cantidades();

        if (! isset($cantidades[$varianteId])) {
            throw ValidationException::withMessages(['carrito' => 'El producto no está en tu carrito.']);
        }

        if ($cantidad < 1) {
            $this->eliminar($varianteId);

            return;
        }

        if ($cantidad > $stock) {
            throw ValidationException::withMessages([
                'cantidad' => 'No hay stock suficiente. Máximo disponible: '.$stock.'.',
            ]);
        }

        $cantidades[$varianteId] = $cantidad;
        $this->guardar($cantidades);
    }

    public function eliminar(int $varianteId): void
    {
        $cantidades = $this->cantidades();
        unset($cantidades[$varianteId]);
        $this->guardar($cantidades);
    }

    public function vaciar(): void
    {
        session()->forget(self::CLAVE);
    }

    public function items(): array
    {
        $cantidades = $this->cantidades();

        if ($cantidades === []) {
            return [];
        }

        $variantes = ProductoVariante::whereIn('id', array_keys($cantidades))
            ->with(['producto', 'talla', 'color', 'inventario'])
            ->get()
            ->keyBy('id');

        $normalizadas = [];
        $detalle = [];

        foreach ($cantidades as $id => $cantidad) {
            $variante = $variantes->get((int) $id);

            if (! $variante || ! $variante->activo || ! $variante->producto?->activo) {
                continue;
            }

            $stock = $variante->inventario?->stock ?? 0;

            if ($stock < 1) {
                continue;
            }

            $cantidad = min(max(1, (int) $cantidad), $stock);
            $normalizadas[$id] = $cantidad;
            $precio = (float) $variante->producto->precio + (float) $variante->precio_adicional;

            $detalle[$id] = [
                'variante' => $variante,
                'cantidad' => $cantidad,
                'stock' => $stock,
                'precio_unitario' => $precio,
                'subtotal' => $precio * $cantidad,
            ];
        }

        if (count($normalizadas) !== count($cantidades)) {
            $this->guardar($normalizadas);
        }

        return $detalle;
    }

    public function cantidadTotal(): int
    {
        return collect($this->items())->sum('cantidad');
    }

    public function subtotal(): float
    {
        return (float) collect($this->items())->sum('subtotal');
    }

    public function costoEnvio(): float
    {
        $subtotal = $this->subtotal();

        if ($subtotal <= 0 || $subtotal >= self::ENVIO_GRATIS) {
            return 0.00;
        }

        return self::COSTO_ENVIO;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->costoEnvio();
    }

    private function cantidades(): array
    {
        $cantidades = session()->get(self::CLAVE, []);

        return is_array($cantidades) ? $cantidades : [];
    }

    private function guardar(array $cantidades): void
    {
        session()->put(self::CLAVE, $cantidades);
    }

    private function buscarVariante(int $varianteId): ProductoVariante
    {
        $variante = ProductoVariante::with(['producto', 'inventario'])->find($varianteId);

        if (! $variante || ! $variante->activo || ! $variante->producto?->activo) {
            throw ValidationException::withMessages(['carrito' => 'El producto seleccionado no está disponible.']);
        }

        return $variante;
    }
}
