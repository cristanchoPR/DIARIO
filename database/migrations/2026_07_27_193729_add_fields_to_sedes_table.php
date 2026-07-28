<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->string('descripcion')->nullable()->after('nombre');
            $table->string('nit')->nullable()->after('descripcion');
            $table->string('direccion')->nullable()->after('nit');
            $table->string('telefono')->nullable()->after('direccion');
            $table->string('email')->nullable()->after('telefono');
            $table->string('logo')->nullable()->after('email');
            $table->string('color')->default('#5A8FDB')->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropColumn(['descripcion', 'nit', 'direccion', 'telefono', 'email', 'logo', 'color']);
        });
    }
};
