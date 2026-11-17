<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'inventario_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
        'referencia',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'stock_anterior' => 'integer',
            'stock_nuevo' => 'integer',
        ];
    }

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'entrada' => 'Entrada',
            'salida' => 'Salida',
            'ajuste' => 'Ajuste',
            'venta' => 'Venta',
            default => ucfirst($this->tipo),
        };
    }
}
