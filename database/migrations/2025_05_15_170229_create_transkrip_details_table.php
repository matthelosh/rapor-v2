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
        Schema::create("transkrip_details", function (Blueprint $table) {
            $table->id();
            $table->string("transkrip_id");
            $table
                ->foreign("transkrip_id")
                ->references("kode")
                ->on("transkrips")
                ->onDelete("cascade");
            $table->string("mapel_id");
            $table
                ->foreign("mapel_id")
                ->references("kode")
                ->on("mapels")
                ->onDelete("cascade");

            $table->decimal("nilai_rapor", 5, 2)->nullable();
            $table->decimal("nilai_psaj", 5, 2)->nullable();
            $table->decimal("nilai_akhir", 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("transkrip_details");
    }
};
