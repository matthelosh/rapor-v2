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
        Schema::create("rapors", function (Blueprint $table) {
            $table->string("siswa_id", 20);
            $table->json("sekolah");
            $table->enum("fase", ["A", "B", "C"]);
            $table->enum("tingkat", ["1", "2", "3", "4", "5", "6"]);
            $table->string("kelas");
            $table->string("semester");
            $table->string("tapel");
            $table->json("nilai_akademik");
            $table->string("nilai_akhir");
            $table->json("ekskul")->nullable();
            $table->text("catatan")->nullable();
            $table->text("keputusan")->nullable();
            $table->json("absensi")->nullable();
            $table->json("ttd");
            $table->date("tanggal_rapor");
            $table->boolean("is_tuntas")->default(true);
            $table->enum("status", ["temp", "permanen"])->default("permanen");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("rapors");
    }
};
