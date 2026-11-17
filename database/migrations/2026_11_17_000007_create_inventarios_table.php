<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_variante_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->timestamps();

            $table->index('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
