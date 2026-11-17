<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_variantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('talla_id')->constrained()->restrictOnDelete();
            $table->foreignId('color_id')->constrained('colores')->restrictOnDelete();
            $table->decimal('precio_adicional', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['producto_id', 'talla_id', 'color_id'], 'variante_unica');
            $table->index('producto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_variantes');
    }
};
