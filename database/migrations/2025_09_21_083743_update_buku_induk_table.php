<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("buku_induks", function (Blueprint $table) {
            $table->string("no_induk", 20)->nullable()->after("siswa_id");
            $table
                ->string("sekolah_tujuan", 100)
                ->nullable()
                ->after("alasan_keluar");
            $table->text("keterangan")->nullable()->after("status");

            // Perbaiki kolom alasan_keluar yang salah penamaan
            if (Schema::hasColumn("buku_induks", "alasan_sekolah")) {
                $table->renameColumn("alasan_sekolah", "alasan_keluar");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("buku_induks", function (Blueprint $table) {
            $table->dropColumn(["no_induk", "sekolah_tujuan", "keterangan"]);

            if (Schema::hasColumn("buku_induks", "alasan_keluar")) {
                $table->renameColumn("alasan_keluar", "alasan_sekolah");
            }
        });
    }
};
