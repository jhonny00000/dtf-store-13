<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained()->restrictOnDelete();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->string('imagen')->default('images/productos/default.svg');
            $table->json('imagenes')->nullable();
            $table->string('codigo')->nullable()->unique();
            $table->boolean('activo')->default(true);
            $table->boolean('destacado')->default(false);
            $table->timestamps();

            $table->index(['activo', 'destacado']);
            $table->index('categoria_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
