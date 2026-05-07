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
        Schema::create("transkrips", function (Blueprint $table) {
            $table->id();
            $table->string("kode", 10)->unique();
            $table->string("sekolah_id");
            $table
                ->foreign("sekolah_id")
                ->references("npsn")
                ->on("sekolahs")
                ->onDelete("cascade");
            $table->string("siswa_id");
            $table
                ->foreign("siswa_id")
                ->references("nisn")
                ->on("siswas")
                ->onDelete("cascade");
            $table->string("tapel");

            $table->decimal("nilai_rapor", 5, 2)->nullable();
            $table->decimal("nilai_psaj", 5, 2)->nullable();
            $table->decimal("nilai_akhir", 5, 2)->nullable();

            $table->string("no_surat")->nullable();
            $table->string("no_ijazah")->nullable();
            $table->date("tanggal_lulus")->nullable();
            $table->date("tanggal_terbit")->nullable();

            $table
                ->enum("status", ["draft", "disetujui", "diterbitkan"])
                ->default("draft");
            $table->string("diterbitkan_oleh", 20)->nullable();
            $table
                ->foreign("diterbitkan_oleh")
                ->references("nip")
                ->on("gurus")
                ->onDelete("cascade");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("transkrips");
    }
};
