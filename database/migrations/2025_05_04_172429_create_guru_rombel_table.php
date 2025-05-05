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
        Schema::create("guru_rombel", function (Blueprint $table) {
            $table->id();
            $table->string("guru_id", 20);
            $table->string("rombel_id", 20);
            $table
                ->foreign("guru_id")
                ->references("nip")
                ->on("gurus")
                ->onDelete("cascade");
            $table
                ->foreign("rombel_id")
                ->references("kode")
                ->on("rombels")
                ->onDelete("cascade");
            // $table->primary(["guru_id", "rombel_id"]);
            $table->enum("status", ["wali", "pengajar"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("guru_rombel");
    }
};
