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
        Schema::create('movimientos_ajustes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventario_id')->constrained('inventarios')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->enum('tipo', ['positivo', 'negativo']);
            $table->decimal('cantidad', 12, 2);
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamp('fecha_hora')->useCurrent();
            $table->string('documento_origen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_ajustes');
    }
};
