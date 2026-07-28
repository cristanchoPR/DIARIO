<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventario_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventarios')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->decimal('existencia_sistema', 12, 2)->default(0.00);
            $table->decimal('costo_sistema', 12, 2)->default(0.00);
            $table->decimal('cantidad_fisica', 12, 2)->nullable();
            $table->decimal('costo_contado', 12, 2)->nullable();
            $table->decimal('valor_total', 12, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['inventario_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_detalles');
    }
};
