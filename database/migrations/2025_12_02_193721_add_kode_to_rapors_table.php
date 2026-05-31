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
        Schema::table('rapors', function (Blueprint $table) {
            $table->string('kode', 191)->primary()->first();
        });
        
        // Tambah index untuk kompatibilitas foreign key
        Schema::table('rapors', function (Blueprint $table) {
            $table->index(['kode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapors', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('kode');
        });
    }
};
