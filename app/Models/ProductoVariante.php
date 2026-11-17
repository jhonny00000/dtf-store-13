<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductoVariante extends Model
{
    use HasFactory;

    protected $table = 'producto_variantes';

    protected $fillable = [
        'producto_id',
        'talla_id',
        'color_id',
        'precio_adicional',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio_adicional' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function talla(): BelongsTo
    {
        return $this->belongsTo(Talla::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class, 'producto_variante_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class, 'producto_variante_id');
    }

    public function getDescripcionVarianteAttribute(): string
    {
        return $this->talla?->nombre.' / '.$this->color?->nombre;
    }
}
