<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_variante_id',
        'stock',
        'stock_minimo',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'stock_minimo' => 'integer',
        ];
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(ProductoVariante::class, 'producto_variante_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function getBajoStockAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function scopeBajoStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }
}
