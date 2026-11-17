<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->text('direccion_entrega');
            $table->string('metodo_pago', 20)->default('efectivo');
            $table->string('estado', 20)->default('pendiente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('notas')->nullable();
            $table->timestamp('fecha_pedido')->useCurrent();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
