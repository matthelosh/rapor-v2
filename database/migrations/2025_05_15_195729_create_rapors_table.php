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
            $table->id();
            $table->string("kode")->unique();
            $table->string("siswa_id");
            $table
                ->foreign("siswa_id")
                ->references("nisn")
                ->on("siswas")
                ->onDelete("cascade");
            $table->enum("semester", ["1", "2"]);
            $table->enum("tingkat", ["1", "2", "3", "4", "5", "6"]);
            $table->string("guru_id");
            $table
                ->foreign("guru_id")
                ->references("nip")
                ->on("gurus")
                ->onDelete("cascade");
            $table->string("tapel", 5);
            $table->string("ks");
            $table->foreign("ks")->references("nip")->on("gurus");
            $table->date("tanggal_rapor")->nullable();
            $table->string("sekolah_id");
            $table
                ->foreign("sekolah_id")
                ->references("npsn")
                ->on("sekolahs")
                ->onDelete("cascade");
            $table->string("rombel_id");
            $table
                ->foreign("rombel_id")
                ->references("kode")
                ->on("rombels")
                ->onDelete("cascade");
            $table->text("ekskuls")->nullable();
            $table->string("absensis")->nullable();
            $table->text("catatan")->nullable();
            $table->timestamps();
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
