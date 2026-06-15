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
        Schema::table('productos', function (Blueprint $table) {

            $table->index('precio');

            $table->index(['nombre', 'precio'], 'productos_nombre_precio_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {

            $table->dropIndex(['precio']);

            $table->dropIndex('productos_nombre_precio_index');

        });
    }
};