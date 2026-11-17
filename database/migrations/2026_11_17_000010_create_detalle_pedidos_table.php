<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('producto_variante_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombre_producto');
            $table->string('talla_nombre');
            $table->string('color_nombre');
            $table->string('imagen')->nullable();
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->index('pedido_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};
