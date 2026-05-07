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
        // Tambahkan migration untuk indexes
        Schema::table('nilais', function (Blueprint $table) {
            $table->index(['tapel', 'semester', 'siswa_id', 'rombel_id', 'mapel_id']);
            $table->index(['siswa_id', 'tipe']);
            $table->index(['rombel_id', 'mapel_id', 'tipe']);
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->index(['sekolah_id', 'status']);
            $table->index('agama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soma_tables', function (Blueprint $table) {
            //
        });
    }
};
