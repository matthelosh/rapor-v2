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
        Schema::create("kaihs", function (Blueprint $table) {
            $table->id();
            $table->string("rombel_id");
            $table->string("siswa_id");
            $table->enum("semester", ["1", "2"]);
            $table->enum("kebiasaan", [
                "Bangun Pagi",
                "Beribadah",
                "Berolahraga",
                "Makan Sehat dan Bergizi",
                "Gemar Belajar",
                "Bermasyarakat",
                "Tidur Cepat",
            ]);
            $table->boolean("is_done")->default(true);
            $table->datetime("waktu")->default(now());
            $table->text("keterangan")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("kaihs");
    }
};
