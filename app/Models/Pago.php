<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'metodo',
        'estado',
        'monto',
        'referencia',
        'fecha_pago',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'fecha_pago' => 'datetime',
        ];
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => 'Pendiente',
            'pagado' => 'Pagado',
            'rechazado' => 'Rechazado',
            default => ucfirst($this->estado),
        };
    }
}
