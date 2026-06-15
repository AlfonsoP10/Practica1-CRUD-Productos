<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function ($table) {
            $table->enum('rol', [
                'admin',
                'editor',
                'cliente'
            ])->default('cliente');
        });
    }

    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('rol');
        });
    }
};