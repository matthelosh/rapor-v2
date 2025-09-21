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
        Schema::table('buku_induks', function (Blueprint $table) {
            // Add indexes for better performance
            $table->index('siswa_id');
            $table->index('status');
            $table->index('no_induk');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_induks', function (Blueprint $table) {
            $table->dropIndex(['siswa_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['no_induk']);
            $table->dropIndex(['status', 'created_at']);
        });
    }
};
