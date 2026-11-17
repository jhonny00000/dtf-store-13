<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Talla extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'orden'];

    public function variantes(): HasMany
    {
        return $this->hasMany(ProductoVariante::class);
    }

    public function scopeOrdenadas(Builder $query): Builder
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }
}
