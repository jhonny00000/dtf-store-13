<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'user_id',
        'cliente_id',
        'direccion_entrega',
        'metodo_pago',
        'estado',
        'subtotal',
        'costo_envio',
        'total',
        'notas',
        'fecha_pedido',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'costo_envio' => 'decimal:2',
            'total' => 'decimal:2',
            'fecha_pedido' => 'datetime',
        ];
    }

    public static array $estados = [
        'pendiente' => 'Pendiente',
        'confirmado' => 'Confirmado',
        'en_preparacion' => 'En preparación',
        'enviado' => 'Enviado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];

    public static array $metodosPago = [
        'efectivo' => 'Efectivo al recibir',
        'transferencia' => 'Transferencia bancaria',
        'qr' => 'Pago con QR',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class);
    }

    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereIn('estado', ['pendiente', 'confirmado', 'en_preparacion']);
    }

    public function getEstadoLabelAttribute(): string
    {
        return self::$estados[$this->estado] ?? ucfirst($this->estado);
    }

    public function getMetodoPagoLabelAttribute(): string
    {
        return self::$metodosPago[$this->metodo_pago] ?? ucfirst($this->metodo_pago);
    }
}
