<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->string('metodo', 20);
            $table->string('estado', 20)->default('pendiente');
            $table->decimal('monto', 10, 2);
            $table->string('referencia')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();

            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
