<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'imagen',
        'imagenes',
        'codigo',
        'activo',
        'destacado',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'imagenes' => 'array',
            'activo' => 'boolean',
            'destacado' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function variantes(): HasMany
    {
        return $this->hasMany(ProductoVariante::class);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeDestacados(Builder $query): Builder
    {
        return $query->where('destacado', true)->where('activo', true);
    }

    public function getStockTotalAttribute(): int
    {
        return (int) $this->variantes()->with('inventario')->get()->sum(function (ProductoVariante $variante) {
            return $variante->inventario?->stock ?? 0;
        });
    }

    public function getGaleriaAttribute(): array
    {
        return collect([$this->imagen])->merge($this->imagenes ?? [])->filter()->unique()->values()->all();
    }
}
