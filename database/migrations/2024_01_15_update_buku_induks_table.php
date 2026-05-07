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
            // Add new columns if they don't exist
            if (!Schema::hasColumn('buku_induks', 'no_induk')) {
                $table->string('no_induk', 20)->nullable()->after('siswa_id');
            }
            if (!Schema::hasColumn('buku_induks', 'sekolah_tujuan')) {
                $table->string('sekolah_tujuan', 100)->nullable()->after('tanggal_keluar');
            }
            if (!Schema::hasColumn('buku_induks', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_induks', function (Blueprint $table) {
            $table->dropColumn(['no_induk', 'sekolah_tujuan', 'keterangan']);
        });
    }
};
