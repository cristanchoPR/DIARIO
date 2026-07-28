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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('sede_id')->constrained('sedes');
            $table->foreignId('usuario_id')->constrained('users');
            $table->enum('estado', ['en_elaboracion', 'guardado', 'finalizado', 'aplicado'])->default('en_elaboracion');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_aplicacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
